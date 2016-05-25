@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Item {{ $item->id }}</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tbody>
                <tr>
                    <th>ID.</th><td>{{ $item->id }}</td>
                </tr>
                <tr><th> {{ trans('items.parentItemId') }} </th><td> {{ $item->parentItemId }} </td></tr><tr><th> {{ trans('items.name') }} </th><td> {{ $item->name }} </td></tr><tr><th> {{ trans('items.salePrice') }} </th><td> {{ $item->salePrice }} </td></tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <a href="{{ url('items/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Item"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['items', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true"/>', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete Item',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ))!!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
@endsection