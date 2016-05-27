@extends('layouts.app')

@section('content')
    <h1>Create New Item</h1>
    <hr/>

    {!! Form::open(['url' => '/items', 'class' => 'form-horizontal']) !!}

        @include('items._form', ['submitButton' => 'Create'])

    {!! Form::close() !!}

@endsection