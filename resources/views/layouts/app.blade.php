<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/vendors.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        window.errors = {!! json_encode(collect($errors->getBag('default'))->map(function($error) {
                return $error[0];
            })) !!}
    </script>
</head>
<body class="">
<div id="@yield('app', 'default-app')" class="d-none">
    <el-container>
        <el-header class="bg-light border-bottom" style="height: 61px;">
            <el-menu class="bg-light" default-active="@yield('active-menu', 0)" class="el-menu-demo" mode="horizontal">
                <el-menu-item index="0" class="mr-5"><a class="d-block" href="{{ url('/') }}">Accueil</a></el-menu-item>
                <el-menu-item index="1"><a class="d-block" href="{{ route('clients.index') }}">Clients</a>
                </el-menu-item>
                <el-menu-item index="2"><a class="d-block" href="{{ route('bills.index') }}">Factures</a></el-menu-item>
            </el-menu>
        </el-header>
        <el-main style="min-height: calc(100vh - 121px)">
            @if ($errors->any())
                <el-alert
                        title="Woops !"
                        type="error"
                        show-icon
                        class="mb-2"
                >
                    <template slot-scope="description">

                        @foreach($errors->all() as $error)
                            <p class="el-alert__description">{{ $error }}</p>
                        @endforeach


                    </template>
                </el-alert>
            @endif
            @if (session('status'))
                <el-alert
                        title="{{ session('status') }}"
                        type="success"
                        show-icon
                        class="mb-2"
                >
                </el-alert>
            @endif
            @yield('body')
        </el-main>
        <el-footer class="d-flex bg-light align-items-center">
            <div class="ml-auto">Copyright {{ date('Y') }}</div>
        </el-footer>
    </el-container>
</div>
<div id="main-loader"
     class="position-absolute d-flex w-100 h-100 flex-column justify-content-center align-items-center">
    <i class="fas fa-5x fa-spinner fa-pulse"></i>
</div>
@stack('scripts')
</body>
</html>