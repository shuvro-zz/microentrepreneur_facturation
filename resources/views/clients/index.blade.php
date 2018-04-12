@extends('layouts.app')

@section('body')
    <el-card class="box-card" header="Clients">
        <div class="text item">
            <el-button type="primary" class="text-white mb-2 p-0"><a class="text-white p-3 d-block" href="{{ route('clients.create') }}"><i class="fas fa-plus-circle"></i> Nouveau </a></el-button>
            <el-table
                    :data="data"
                    stripe
                    style="width: 100%"
                    v-if="data.length"
            >
                <el-table-column
                        prop="company_name"
                        label="Raison social"
                >
                </el-table-column>
                <el-table-column
                        prop="siren"
                        label="SIREN"
                >
                </el-table-column>
                <el-table-column
                        label="Actions"
                >
                    <template slot-scope="scope">
                        <el-button type="text" size="small"><a :href="scope.row.editUrl">Edit</a></el-button>
                        <el-button type="text" size="small"><a :href="scope.row.deleteUrl">Supprimer</a></el-button>
                    </template>
                </el-table-column>
            </el-table>
            <el-alert
                    v-else
                    title="Pas de client"
                    type="warning"
                    :closable="false"
            >
            </el-alert>
        </div>
    </el-card>

@endsection

@push('scripts')
    <script>
        window.data = {!! json_encode($clients->map(function($c) {
            $c->editUrl = route('clients.edit', ['id' => $c->id]);
            $c->deleteUrl = route('clients.destroy', ['id' => $c->id]);
            return $c;
        })) !!}
    </script>
    <script src="{{ asset('js/default-app.js') }}" defer></script>
@endpush