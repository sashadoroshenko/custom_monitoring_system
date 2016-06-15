@extends('layouts.app')

@section('content')
    <h1>Walmart-api-keys
        <a href="{{ url('/walmart-api-keys/create') }}" class="btn btn-primary btn-xs" title="Add New Walmart-api-key">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        </a>
    </h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th><th> {{ trans('walmart-api-keys.key') }} </th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            {{-- */$x=0;/* --}}
            @foreach($walmartapikeys as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    <td>{{ $item->key }}</td>
                    <td>
                        <a href="{{ url('/walmart-api-keys/' . $item->id) }}" class="btn btn-success btn-xs" title="View Walmart-api-key"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"/></a>
                        <a href="{{ url('/walmart-api-keys/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Walmart-api-key"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['/walmart-api-keys', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true" title="Delete Walmart-api-key" />', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete Walmart-api-key',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ))!!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $walmartapikeys->render() !!} </div>
    </div>
@endsection
