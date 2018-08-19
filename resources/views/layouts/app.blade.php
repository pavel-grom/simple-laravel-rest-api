<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href=  "https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li v-if="$auth.check()" tag="li">
                            <span class="nav-link">@{{ $auth.user().name }}</span>
                        </li>

                        <!-- Authentication Links -->
                        <router-link :to="{ name: 'register' }" v-if="!$auth.check()" tag="li">
                            <a class="nav-link">Register</a>
                        </router-link>

                        <router-link :to="{ name: 'login' }" v-if="!$auth.check()" tag="li">
                            <a class="nav-link">Login</a>
                        </router-link>

                        <li v-if="$auth.check()" tag="li">
                            <a href="#" class="nav-link" @click.prevent="$auth.logout()">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container">
            @yield('content')
        </main>
    </div>
</body>
</html>
