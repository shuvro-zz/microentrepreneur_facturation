@extends('layouts.app')

@section('active-menu', 2)
@section('app', 'bill')

@section('body')
    <el-card class="box-card" header="Factures">
        <div class="text item">
            <el-button type="primary" class="text-white mb-2 p-0"><a class="text-white p-3 d-block"
                                                                     href="{{ route('bills.create') }}"><i
                            class="fas fa-plus-circle"></i> Nouveau </a></el-button>
            <el-table
                    :data="data"
                    stripe
                    style="width: 100%"
                    v-if="data.length"
            >
                <el-table-column
                        prop="id"
                        label="#"
                >
                </el-table-column>
                <el-table-column
                        prop="created_at"
                        label="Date"
                >
                </el-table-column>
                <el-table-column
                        prop="client.company_name"
                        label="Raison social"
                >
                </el-table-column>
                <el-table-column
                        prop="client.siren"
                        label="SIREN"
                >
                </el-table-column>
                <el-table-column
                        prop="totalPrice"
                        label="Montant"
                >
                </el-table-column>
                <el-table-column
                        label="Actions"
                >
                    <template slot-scope="scope">
                        <el-button v-if="scope.row.draft" type="primary" size="small"><a class="text-white" :href="scope.row.showUrl"><i
                                        class="fas fa-eye"></i></a></el-button>

                        <el-button v-if="!scope.row.draft" type="primary" size="small"><a class="text-white" :href="scope.row.gd_web_view_link" target="_blank"><i
                                        class="fas fa-eye"></i></a></el-button>

                        <el-form v-if="!scope.row.paid_at && !scope.row.draft" class="d-inline-block" :model="scope.row" :action="scope.row.paidUrl" method="post">
                            @csrf
                            <el-button type="primary" native-type="submit" size="small"><a class="text-white" :href="scope.row.showUrl"><i
                                            class="fas fa-money-bill-alt"></i></a></el-button>
                        </el-form>
                    </template>
                </el-table-column>
            </el-table>
            <el-alert
                    v-else
                    title="Pas de factures"
                    type="warning"
                    :closable="false"
            >
            </el-alert>
        </div>
    </el-card>

@endsection

@push('scripts')
    <script>
        window.data = {!! json_encode($bills->map(function($b) {
            $b->showUrl = route('bills.show', ['id' => $b->id]);
            $b->paidUrl = route('bills.paid', ['id' => $b->id]);
            $b->totalPrice = collect($b->total_price)->map(function($p, $currency) {
                return $p . ' ' .$currency;
            })->implode(' - ');
            return $b;
        })) !!}
    </script>
    <script src="{{ asset('js/bill.js') }}" defer></script>
@endpush