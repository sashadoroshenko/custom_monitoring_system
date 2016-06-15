@extends('layouts.app')

@section('content')

    <h1>Item "{{ $item->title }}"</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tbody>
            <tr>
                <th>ID.</th>
                <td>{{ $item->id }}</td>
            </tr>
            <tr>
                <th> Item ID </th>
                <td> {{ $item->itemID }} </td>
            </tr>
            <tr>
                <th> Title </th>
                <td> {{ $item->title }} </td>
            </tr>
            <tr>
                <th> Price </th>
                <td>
                    <ol>
                        @foreach($item->prices()->orderBy('status', 'desc')->orderBy('created_at', 'desc')->get() as $price)
                            <li>
                                <span class="label @if($price->status) label-primary @else label-warning @endif">{{$price->price}} / {{ showCurrentDateTime($price->updated_at) }}</span>
                            </li>
                        @endforeach
                    </ol>
                </td>
            </tr>
            <tr>
                <th> Stock </th>
                <td>
                    <ol>
                        @foreach($item->stocks()->orderBy('status', 'desc')->orderBy('created_at', 'desc')->get() as $stock)
                            <li>
                                <span class="label @if($stock->status) label-primary @else label-warning @endif">{{$stock->stock}} / {{ showCurrentDateTime($stock->updated_at) }}</span>
                            </li>
                        @endforeach
                    </ol>
                </td>
            </tr>
            <tr>
                <th> Updated </th>
                <td> {{ $item->stocks()->where('status', 1)->first()->updated_at == $item->prices()->where('status', 1)->first()->updated_at ? showCurrentDateTime($item->stocks()->where('status', 1)->first()->updated_at) : ($item->stocks()->where('status', 1)->first()->updated_at > $item->prices()->where('status', 1)->first()->updated_at ? showCurrentDateTime($item->stocks()->where('status', 1)->first()->updated_at) : showCurrentDateTime($item->prices()->where('status', 1)->first()->updated_at)) }} </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">
                    <a href="{{ url('items/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs"
                       title="Edit Item"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
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

@endsection