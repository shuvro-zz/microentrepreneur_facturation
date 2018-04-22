@extends('layouts.app')

@section('active-menu', 2)

@section('body')
    @if($bill->draft)
    <el-alert title="" type="warning" "></el-alert>
    <el-card header="Facture #{{$bill->id}}" class="bg-dark text-white">
        <div style="width: 21cm; height: 29.7cm" class="m-auto border bg-white text-dark">
            @include('bills.pdf', ['bill' => $bill])
        </div>
        <el-row class="mt-3">
            <el-col :span="12" class="d-flex justify-content-center">
                @if($bill->draft)
                    <el-button type="primary"><a class="text-white"
                                                 href="{{ route('bills.edit', ['id' => $bill->id]) }}">Editer</a>
                    </el-button>
                @endif
            </el-col>
            <el-col :span="12" class="d-flex justify-content-center">
                <form action="{{ route('bills.emit', ['id' => $bill->id]) }}" method="post">
                    @csrf
                    <el-button native-type="submit" type="success">Valider</el-button>
                </form>
            </el-col>
        </el-row>
    </el-card>
    @else
    <el-alert title="Facture déjà émise" type="warning"></el-alert>
    @endif

@endsection

@push('scripts')
    <script src="{{ asset('js/default-app.js') }}" defer></script>
@endpush