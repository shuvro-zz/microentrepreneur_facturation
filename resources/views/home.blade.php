@extends('layouts.app')

@section('app', 'default-app')

@section('body')
    <el-row :gutter="20" class="h-100 d-flex">
        <el-col :md='12' class="d-flex flex-column justify-content-center">
            <el-card class="box-card h-50 d-flex flex-column justify-content-center align-items-center bg-info" style="min-height: 200px">
                <div class="text item">
                    <el-button  type="text"><a class="text-dark" href="{{ route('clients.index') }}">Clients</a></el-button>
                </div>
            </el-card>
        </el-col>
        <el-col :md='12' class="d-flex flex-column justify-content-center">
            <el-card class="box-card h-50 d-flex flex-column justify-content-center align-items-center bg-info" style="min-height: 200px">
                <div class="text item">
                    <el-button href="route('bills')" type="text"><a class="text-dark" href="{{ route('bills.index') }}">Factures</a></el-button>
                </div>
            </el-card>
        </el-col>
    </el-row>
@endsection


@push('scripts')
    <script src="{{ asset('js/default-app.js') }}" defer></script>
@endpush