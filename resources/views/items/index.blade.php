@extends('layouts.app')

@section('content')
    <link href="{{ asset('/css/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .checkbox {
            display: block !important;
        }
    </style>

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
                <th> Item ID </th>
                <th> Title </th>
                <th> User ID </th>
                <th> User </th>
                <th> Price </th>
                <th> Stock </th>
                <th> Alert System </th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            {{-- */$x=0;/* --}}
            @foreach($items as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    <td>{{ $item->itemID }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->userID }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>${{ $item->price }}</td>
                    <td>{{ $item->stock }}</td>
                    <td>
                        <div class="checkbox">
                            <label><i class="glyphicon @if($item->alert_desktop)  glyphicon-check @else glyphicon-unchecked @endif"></i> : Desktop</label>
                        </div>
                        <div class="checkbox">
                            <label><i class="glyphicon @if($item->alert_email)  glyphicon-check @else glyphicon-unchecked @endif"></i> : Email</label>
                        </div>
                        <div class="checkbox disabled">
                            <label><i class="glyphicon @if($item->alert_sms)  glyphicon-check @else glyphicon-unchecked @endif"></i> : SMS</label>
                        </div>

                    </td>
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
@endsection
@section('scripts')
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
                    url: "{{url('items/showDesktopAlerts')}}",
                    type: "POST",
                    data: { _token: "{{csrf_token()}}"},
                    dataType: "json",
                    success: function (data, textStatus, jqXHR) {
                        if(data.status == "error"){
                            return;
                        }
                        var message = '';
                        data.forEach(function (element, index, array) {

                            if(element.status === 200) {
                                message += "Product With ID " + element.itemID + "don't Change price. New Prise - <strong>" + element.newPrice + "</strong>, Old Price - <strong>" + element.oldPrice + "</strong>";
                            }else{
                                message += "Product With ID " + element.itemID + " Change price. New Prise - <strong>" + element.newPrice + "</strong>, Old Price - <strong>" + element.oldPrice + "</strong>";
                            }
                            message += "<br>";
                        });

                        swal({
                            title: "<small class='text-danger'>Monitoring System Alerts <i class='glyphicon glyphicon-alert'></i></small>",
                            text: message,
                            html: true,
                            timer: 10000
                        });
//                        console.log(data);
                        timedRefresh(60000);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR)
                    }
                });

            }

            $('#example').DataTable();
        });

    </script>
@endsection
