@extends('layouts.app')

@section('content')
    <link href="{{ asset('/css/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <h1>Items <a href="{{ url('/items/create') }}" class="btn btn-primary btn-xs" title="Add New Item"><span class="glyphicon glyphicon-plus" aria-hidden="true"/></a></h1>
                        <hr>
                        <table id="example" class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>ItemID</th>
                                <th>UserID</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>{{$alert->ItemID}}</td>
                                    <td>{{$alert->userID}}</td>
                                    <td>{{$alert->price}}</td>
                                    <td>
                                        <a href="{{ url('/items/' . $item->id) }}" class="btn btn-success btn-xs" title="View Item"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"/></a>
                                        <a href="{{ url('/items/' . $item->id . '/edit') }}" class="btn btn-primary btn-xs" title="Edit Item"><span class="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
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
                            <tfoot>
                            <tr>
                                <th>ItemID</th>
                                <th>UserID</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
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
                    url: "/",
                    type: "POST",
                    success: function (data) {
                        alert(data);
                        timedRefresh(60000);
                    }
                });

            }

            $('#example').DataTable();
        });

    </script>
@endsection