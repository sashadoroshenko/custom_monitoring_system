@extends('layouts.auth')

@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>Admin</b>LTE</a>
        </div><!-- /.login-logo -->

        @include('layouts.partials.message')

        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <form action="{{ url('/login') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" name="email"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" name="password"/>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="remember"> Remember Me
                            </label>
                        </div>
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div><!-- /.col -->
                </div>
            </form>

            <a href="{{ url('/register') }}" class="text-center">Register a new membership</a>

        </div><!-- /.login-box-body -->

    </div><!-- /.login-box -->

@endsection

@section('scripts')
    @include('layouts.partials.scripts_auth')

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection