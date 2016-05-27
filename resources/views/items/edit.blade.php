@extends('layouts.app')

@section('content')
    <h1>Edit Item "{{ $item->title }}"</h1>

    {!! Form::model($item, [
        'method' => 'PATCH',
        'url' => ['/items' , $item->id],
        'class' => 'form-horizontal'
    ]) !!}

    @include('items._form', ['submitButton' => 'Update'])

    {!! Form::close() !!}

@endsection