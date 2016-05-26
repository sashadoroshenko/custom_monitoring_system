@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Edit Item {{ $item->id }}</h1>

        {!! Form::model($item, [
            'method' => 'PATCH',
            'url' => ['/items' , $item->id],
            'class' => 'form-horizontal'
        ]) !!}


        @include('items._form', ['submitButton' => 'Update'])

        {!! Form::close() !!}

    </div>
@endsection