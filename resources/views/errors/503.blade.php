@extends('layouts.app')

@section('content')

    <div class="error-page">
        <h2 class="headline text-red">503</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> Oops! Something went wrong.</h3>
            <p>
                We will work on fixing that right away. Meanwhile, you may
                <a href="{{ url('dashboard') }}">return to dashboard</a>.
            </p>
        </div>
    </div><!-- /.error-page -->
@endsection