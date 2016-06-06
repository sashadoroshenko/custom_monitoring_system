@extends('layouts.app')

@section('content')

    <h1>Edit Walmart-api-key {{ $walmartapikey->id }}</h1>

    {!! Form::model($walmartapikey, [
        'method' => 'PATCH',
        'url' => ['/walmart-api-keys', $walmartapikey->id],
        'class' => 'form-horizontal'
    ]) !!}

    <div class="form-group {{ $errors->has('key') ? 'has-error' : ''}}">
        {!! Form::label('key', trans('walmart-api-keys.key'), ['class' => 'col-sm-3 control-label']) !!}
        <div class="col-sm-6">
            {!! Form::text('key', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('key', '<p class="help-block">:message</p>') !!}
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
        </div>
    </div>
    {!! Form::close() !!}

@endsection