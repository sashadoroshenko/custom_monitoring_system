@extends('layouts.app')

@section('content')
    <link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <h1>Edit User {{ $profile->id }}</h1>

    {!! Form::model($profile, [
        'method' => 'post',
        'url' => ['/profile'],
        'class' => 'form-horizontal'
    ]) !!}

    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        {!! Form::label('name', 'User name', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter user name']) !!}
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
        {!! Form::label('email', 'User email', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required', 'readonly', 'placeholder' => 'Enter user email']) !!}
            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('phone') ? 'has-error' : ''}}">
        {!! Form::label('phone', 'User phone', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('phone', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter user phone']) !!}
            {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('location') ? 'has-error' : ''}}">
        {!! Form::label('phone', 'User location', ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::select('location', $locations, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select location']) !!}
            {!! $errors->first('location', '<p class="help-block">:message</p>') !!}
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
        </div>
    </div>
    {!! Form::close() !!}
@endsection
@section('scripts')
    <script src="{{ asset('/js/select2.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $('select').select2();
    </script>
@endsection