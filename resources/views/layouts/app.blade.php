<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/sweetalert.css')}}">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Laravel
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
{{--                    <li class="@if(request()->is('/')) active @endif"><a href="{{ url('/') }}">Home</a></li>--}}
                    @if (!Auth::guest())
                        <li class="@if(request()->is('items')) active @endif"><a href="{{ url('/items') }}">Items</a></li>
                        <li class="@if(request()->is('walmart-api-keys')) active @endif"><a href="{{ url('/walmart-api-keys') }}">Walmart API Keys</a></li>
                        <li class="@if(request()->is('logs')) active @endif"><a href="{{ url('/logs') }}">Logs</a></li>
                        {{--<li class="dropdown">--}}
                            {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">--}}
                                {{--Walmart functionality <span class="caret"></span>--}}
                            {{--</a>--}}

                            {{--<ul class="dropdown-menu" role="menu">--}}
                                {{--<li><a href="{{ url('/home/search') }}">Search</a></li>--}}
                                {{--<li><a href="{{ url('/home/item') }}">Item</a></li>--}}
                                {{--<li><a href="{{ url('/home/reviews') }}">Reviews</a></li>--}}
                                {{--<li><a href="{{ url('/home/taxonomy') }}">Taxonomy</a></li>--}}
                                {{--<li><a href="{{ url('/home/vod') }}">VOD</a></li>--}}
                                {{--<li><a href="{{ url('/home/stores') }}">Stores</a></li>--}}
                                {{--<li><a href="{{ url('/home/trends') }}">Trends</a></li>--}}
                                {{--<li><a href="{{ url('/home/paginate') }}">Paginate</a></li>--}}
                                {{--<li><a href="{{ url('/home/recommendation') }}">Recommendation</a></li>--}}
                                {{--<li><a href="{{ url('/home/postBrowsed') }}">Post Browsed</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}

                        {{--<li><a href="{{ url('/home/dataFeed') }}">Data Feed</a></li>--}}
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('login') }}">Login</a></li>
                        <li><a href="{{ url('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    @if(auth()->check())
        @include('layouts.partials.errors')
    @endif

    <div class="container-fluid">
        @yield('content')
    </div>

    <!-- JavaScripts -->
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert-dev.js') }}"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    @yield('scripts')
</body>
</html>
