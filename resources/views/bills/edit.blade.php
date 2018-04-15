@extends('layouts.app')

@section('active-menu', 2)
@section('app', 'bill')

@section('body')
    @if (!$bill->draft)
        <el-alert title="Woops" description="Vous ne pouvez plus editer cette facture">
        </el-alert>
    @else

        <el-card class="box-card" header="Facture #">
            <div class="text item">
                <el-form ref="form" :model="bill" method="post" action="{{ route('bills.store') }}">
                    @csrf
                    <input type="hidden" name="client_id" :value="client_id">
                    <el-form-item :error="errors['client_id']">
                        <el-row>
                            <el-col :span="24" class="pr-2" :sm="{span: 6}">
                                <label class="d-block w-100">Client</label>
                            </el-col>
                            <el-col :span="24" :sm="{span: 18}">
                                <el-select class="w-100" no-match-text="Pas de résultat" v-model="bill.client"
                                           placeholder="Select" name="client" :required="true">
                                    <el-option
                                            v-for="client in clients"
                                            :key="client.id"
                                            :label="client.company_name"
                                            :value="client.id">
                                    </el-option>
                                </el-select>
                            </el-col>
                        </el-row>
                    </el-form-item>
                    <el-row :gutter="10" class="my-4">
                        <el-col :span="24" class="pr-2" :sm="{span: 6}">
                            Prestations
                        </el-col>
                        <el-col :span="24" class="pr-2" :sm="{span: 6}">
                            Libellé
                        </el-col>
                        <el-col :span="24" class="pr-2" :sm="{span: 4}">
                            Quantité
                        </el-col>
                        <el-col :span="24" class="pr-2" :sm="{span: 6}">
                            Prix unitaire
                        </el-col>
                    </el-row>

                    <el-row v-for="(benefit, idx) in bill.benefits" :gutter="10" class="mt-4 m-sm-0" :key="idx">
                        <el-col :span="24" :sm="{span: 6, offset: 6}">
                            <el-form-item :error="errors['benefits']">
                                <el-autocomplete
                                        class="inline-input w-100"
                                        v-model="benefit.value"
                                        :fetch-suggestions="querySearch"
                                        placeholder="Prestation"
                                        :trigger-on-focus="false"
                                        :name="'benefits[' + idx + '][value]'"
                                ></el-autocomplete>
                            </el-form-item>
                        </el-col>

                        <el-col :span="24" :sm="{span: 4}">
                            <el-form-item>
                                <el-input-number :name="'benefits[' + idx + '][quantity]'" placeholder="Quantité"
                                                 class="w-100" v-model="benefit.quantity" :min="1"></el-input-number>
                            </el-form-item>
                        </el-col>
                        <el-col :span="24" :sm="{span: 6}">
                            <el-form-item :error="error(idx, 'unit_price') || error(idx, 'currency')">
                                <el-input placeholder="Prix unitaire" :name="'benefits[' + idx + '][unit_price]'"
                                          v-model="benefit.unit_price" class="input-with-select w-100"
                                          @focus="addBenfit(idx)">
                                    <el-select :name="'benefits[' + idx + '][currency]'" v-model="benefit.currency"
                                               slot="append" placeholder="Devise" style="width: 100px">
                                        @foreach(collect(config('billing.currencies')) as $key => $currency)
                                            <el-option label="{{ $currency['symbol'] }}" value="{{ $key }}"></el-option>
                                        @endforeach
                                    </el-select>
                                </el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="24" :sm="{span: 2}" v-if="idx > 0">
                            <el-button type="danger" @click="removeBenefit(idx)"><i class="fas fa-trash"></i>
                            </el-button>
                        </el-col>
                    </el-row>
                    </el-form-item>
                    <el-form-item>
                        <el-row>
                            <el-col :span="24" :sm="{offset: 10, span: 6}">
                                <el-button native-type="submit" type="primary" class="w-100">Enregistrer <i
                                            class="fas fa-save"></i></el-button>
                            </el-col>
                        </el-row>
                    </el-form-item>

                </el-form>
            </div>
        </el-card>
    @endif
@endsection

@push('scripts')
    <script>
        window.clients = {!! json_encode($clients) !!};
        window.benefits = {!! json_encode($benefits) !!};
        window.bill = {!! json_encode($bill->merge(collect(old())) !!}

    </script>
    <script src="{{ asset('js/bill.js') }}" defer></script>
@endpush