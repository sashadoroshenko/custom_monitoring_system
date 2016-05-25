@extends('layouts.app')

@section('content')
    <link href="{{ asset('/css/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <div class="container">

        <h1>Items
            <a href="{{ url('/items/create') }}" class="btn btn-primary btn-xs" title="Add New Item">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"/>
            </a>
        </h1>
        <div class="table">
            <table id="example" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th>S.No</th>
                    <th> {{ trans('items.parentItemId') }} </th>
                    <th> {{ trans('items.name') }} </th>
                    <th> {{ trans('items.salePrice') }} </th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                {{-- */$x=0;/* --}}
                @foreach($items as $item)
                    {{-- */$x++;/* --}}
                    <tr>
                        <td>{{ $x }}</td>
                        <td>{{ $item->parentItemId }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->salePrice }}</td>
                        <td>
                            <a href="{{ url('/items/' . $item->id) }}" class="btn btn-success btn-xs" title="View Item">
                                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"/>
                            </a>
                            <a href="{{ url('/items/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Item">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"/>
                            </a>
                            {!! Form::open([
                                'method'=>'DELETE',
                                'url' => ['/items', $item->id],
                                'style' => 'display:inline'
                            ]) !!}
                            {!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true" title="Delete Item" />', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete Item',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ))!!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination"> {!! $items->render() !!} </div>
        </div>

    </div>
    <script src="{{ asset('/js/jquery.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/dataTables.bootstrap.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            timedRefresh(1000);
            function timedRefresh(timeoutPeriod) {
                setTimeout(Update, timeoutPeriod);
            }

            function Update() {
                $.ajax({
                    url: "{{url('items/showAlert')}}",
                    type: "POST",
                    data: { _token: "{{csrf_token()}}"},
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        timedRefresh(60000);
                    }
                });

            }

            $('#example').DataTable();
        });

    </script>
@endsection
