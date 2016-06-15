@extends('layouts.app')

@section('content')

    <h1>Walmart-api-key {{ $walmartapikey->id }}</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tbody>
            <tr>
                <th>ID.</th>
                <td>{{ $walmartapikey->id }}</td>
            </tr>
            <tr>
                <th> {{ trans('walmart-api-keys.key') }} </th>
                <td> {{ $walmartapikey->key }} </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">
                    <a href="{{ url('walmart-api-keys/' . $walmartapikey->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Walmart-api-key">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"/>
                    </a>
                    {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['walmart-api-keys', $walmartapikey->id],
                        'style' => 'display:inline'
                    ]) !!}
                    {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"/>', array(
                            'type' => 'submit',
                            'class' => 'btn btn-danger btn-xs',
                            'title' => 'Delete Walmart-api-key',
                            'onclick'=>'return confirm("Confirm delete?")'
                    ))!!}
                    {!! Form::close() !!}
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
@endsection