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
                {{--<th> User ID </th>--}}
                {{--<th> User </th>--}}
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
{{--                    <td>{{ $item->userID }}</td>--}}
{{--                    <td>{{ $item->user->name }}</td>--}}
                    <td><a data-id="{{ $item->id }}" data-item-id="{{ $item->itemID }}" href="#" class="showPrice price {{$item->id}}">${{ $item->prices()->where('status', 1)->first()->price or "0" }}</a></td>
                    <td class="stock {{$item->id}}">{{ $item->stock }}</td>
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

    @include('layouts.partials.modal')

@endsection
@section('scripts')
    <script src="{{ asset('/js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/dataTables.bootstrap.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
//            timedRefresh(1000);
            refreshContent(5000);

            function timedRefresh(alertTimeout) {
                setTimeout(showAlert, alertTimeout);
            }

            function refreshContent(updateTimeout) {
                setTimeout(updateContent, updateTimeout);
            }

            function showAlert() {
                $.ajax({
                    url: "{{url('items/showDesktopAlerts')}}",
                    type: "POST",
                    data: { _token: "{{csrf_token()}}"},
                    dataType: "json",
                    success: function (data, textStatus, jqXHR) {
                        if(data.length > 0) {
                            var message = '';
                            data.forEach(function (element, index, array) {

                                if (element.status === 200) {
                                    message += "Product With ID " + element.itemID + "don't Change price. New Prise - <strong>" + element.newValue + "</strong>, Old Price - <strong>" + element.oldValue + "</strong>";
                                } else {
                                    message += "Product With ID " + element.itemID + " Change price. New Prise - <strong>" + element.newValue + "</strong>, Old Price - <strong>" + element.oldValue + "</strong>";
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
                        }
                        timedRefresh(60000);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        timedRefresh(60000);
                    }
                });

            }

            function updateContent() {
                $.ajax({
                    url: "{{url('items/updateContent')}}",
                    type: "POST",
                    data: { _token: "{{csrf_token()}}"},
                    dataType: "json",
                    success: function (data, textStatus, jqXHR) {
                        if (!$.isEmptyObject(data)) {
                        console.log(data);
                            var stock = data.stock;
                            var price = data.price;
                            console.log($.isEmptyObject(stock))
                            stock.forEach(function (element, index, array) {
                                if (!$.isEmptyObject(element)) {
                                    console.log(element)
                                    $('#example').find(".stock." + element[0].id).html(element[0].newValue);
                                }
                            });

                            price.forEach(function (element, index, array) {
                                if (!$.isEmptyObject(element)) {
                                    console.log(element)
                                    $('#example').find(".price." + element[0].id).html('$' + element[0].newValue);
                                }
                            });
                        }
//                        console.log(data);
                        refreshContent(60000);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('error');
                        console.log(jqXHR);
                        refreshContent(60000);
                    }
                });

            }

            $('#example').DataTable();


            $('.showPrice').on('click', function (e) {
                var id = $(this).attr('data-id');
                var itemID = $(this).attr('data-item-id');
                $.ajax({
                    url: "/price-history/" + id,
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{csrf_token()}}"
                    },
                    dataType: "html",
                    success: function (data, textStatus, jqXHR) {
                        $('#modalTitle').html('Price history for <strong>' + itemID + '</strong> item!');
                        $('#modalContent').html(data);
                        $("#myModal").modal('show');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR)
                    }
                });
            })
        });

    </script>
@endsection
