<?php

namespace App\Http\Controllers;

use App\Benefit;
use App\Models\Bill;
use App\Models\Client;
use App\Notifications\BillAvailable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bills = Bill::with('client')->get();
        return view('bills.index', ['bills' => $bills]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients  = Client::all();
        $benefits = Benefit::all();
        return view('bills.create', ['clients' => $clients, 'benefits' => $benefits]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id'            => 'required:exists:clients,id',
            'designation'          => 'required',
            'benefit.*.value'      => 'sometimes',
            'benefit.*.quantity'   => 'sometimes|integer|min:0',
            'benefit.*.unit_price' => 'sometimes|numeric|min:0',
            'benefit.*.currency'   => [
                'sometimes',
                Rule::in(collect(config('billing.currencies'))->keys()),
            ]
        ]);

        $validator->after(function ($validator) {
            $benefits = request()->input('benefits');
            $c        = collect($benefits)->filter(function ($b) {
                return !empty($b['value']);
            });
            if ($c->isEmpty()) {
                $validator->errors()->add('benefits', 'No benefits found');
            }
            foreach ($benefits as $idx => $benefit) {
                if (!empty($benefit['value'])) {
                    if (empty($benefit['quantity'])) {
                        $validator->errors()->add('benefit_'.$idx.'_quantity', 'Quantity is required');
                    }
                    if (empty($benefit['unit_price'])) {
                        $validator->errors()->add('benefit_'.$idx.'_unit_price', 'Price is required');
                    }
                    if (empty($benefit['currency'])) {
                        $validator->errors()->add('benefit_'.$idx.'_currency', 'Currency is required');
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect('bills/create')
                ->withErrors($validator)
                ->withInput();
        }


        \DB::beginTransaction();
        try {
            $bill = Bill::create(['client_id' => request()->input('client_id')]);
            foreach (request()->input('benefits') as $benefit) {
                if (!empty($benefit['value'])) {
                    $model = Benefit::firstOrCreate(['value' => $benefit['value']]);
                    $bill->benefits()->attach($model->id, [
                        'currency'   => $benefit['currency'],
                        'unit_price' => $benefit['unit_price'],
                        'quantity'   => $benefit['quantity']
                    ]);
                }
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
        return redirect()->route('bills.show', ['id' => $bill->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bill = Bill::findOrFail($id);
        return view('bills.show', ['bill' => $bill]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bill = Bill::findOrFail($id);
        $bill->load('client');
        $bill->load('benefits');
        $clients  = Client::all();
        $benefits = Benefit::all();
        return view('bills.edit', ['bill' => $bill, 'clients' => $clients, 'benefits' => $benefits]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $bill = Bill::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'client_id'            => 'required:exists:clients,id',
            'designation'          => 'required',
            'benefit.*.value'      => 'sometimes',
            'benefit.*.quantity'   => 'sometimes|integer|min:0',
            'benefit.*.unit_price' => 'sometimes|numeric|min:0',
            'benefit.*.currency'   => [
                'sometimes',
                Rule::in(collect(config('billing.currencies'))->keys()),
            ]
        ]);

        $validator->after(function ($validator) {
            $benefits = request()->input('benefits');
            $c        = collect($benefits)->filter(function ($b) {
                return !empty($b['value']);
            });
            if ($c->isEmpty()) {
                $validator->errors()->add('benefits', 'No benefits found');
            }
            foreach ($benefits as $idx => $benefit) {
                if (!empty($benefit['value'])) {
                    if (empty($benefit['quantity'])) {
                        $validator->errors()->add('benefit_'.$idx.'_quantity', 'Quantity is required');
                    }
                    if (empty($benefit['unit_price'])) {
                        $validator->errors()->add('benefit_'.$idx.'_unit_price', 'Price is required');
                    }
                    if (empty($benefit['currency'])) {
                        $validator->errors()->add('benefit_'.$idx.'_currency', 'Currency is required');
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->route('bills.edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }


        \DB::beginTransaction();
        try {
            $bill = $bill->fill([
                'client_id' => request()->input('client_id'),
                'designation' => request()->input('designation'),
            ]);
            $bill->benefits()->detach();
            foreach (request()->input('benefits') as $benefit) {
                if (!empty($benefit['value'])) {
                    $model = Benefit::firstOrCreate(['value' => $benefit['value']]);
                    $bill->benefits()->attach($model->id, [
                        'currency'   => $benefit['currency'],
                        'unit_price' => $benefit['unit_price'],
                        'quantity'   => $benefit['quantity']
                    ]);
                }
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }
        return redirect()->route('bills.show', ['id' => $bill->id]);
    }

    public function emit(Request $request, $id)
    {
        $bill = Bill::findOrFail($id);
        $bill->draft = 0;
        $bill->save();
        $bill->savePdf();
        $bill->client->notify(new BillAvailable($bill));
        return redirect()->route('bills.index')->with('status', 'Facture publiée');;
    }

    public function paid(Request $request, $id)
    {
        $bill = Bill::findOrFail($id);
        $bill->paid(Carbon::now());
        return redirect()->route('bills.index')->with('status', 'Facture payée');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
