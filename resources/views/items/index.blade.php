@extends('layouts.app')

@section('content')
    <link href="{{ asset('/css/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .checkbox {
            display: block !important;
        }
        #last-updated-text{
            font-size: medium;
        }
    </style>

    <h1>Items
        <a href="{{ url('/items/create') }}" class="btn btn-primary btn-xs" title="Add New Item">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"/>
        </a>
        <span id="last-updated-text" class="pull-right">Last Updated: <span id="last-updated-date-time"></span></span>
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
                <th> Last Update </th>
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
                    <td><a href="{{ $item->url }}" target="_blank">{{ $item->title }}</a></td>
{{--                    <td>{{ $item->userID }}</td>--}}
{{--                    <td>{{ $item->user->name }}</td>--}}
                    <td>
                        <a data-id="{{ $item->id }}" data-item-id="{{ $item->itemID }}" href="#" class="showPrice price {{$item->id}}">
                            ${{ $item->prices()->where('status', 1)->first()->price or "0" }}
                        </a>
                    </td>
                    <td>
                        <a data-id="{{ $item->id }}" data-item-id="{{ $item->itemID }}" href="#" class="showStock stock {{$item->id}}">
                            {{ $item->stocks()->where('status', 1)->first()->stock or "Not Available" }}
                        </a>
{{--                        {{ $item->stock }}--}}
                    </td>
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
                    <td class="updated-at {{ $item->id }}" data-updated-at="{{$item->updated_at}}"></td>
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
    </div>
    <audio id="myAudio" controls style="display: none">
        <source src="/sounds/sound1.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    @include('layouts.partials.modal')

@endsection
@section('scripts')
    <script src="{{ asset('/js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/dataTables.bootstrap.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#example').DataTable({
                "pageLength": 50
            });
            //initialize functions for first start
//            timedRefresh(1000);
            refreshContent(5000);
            updateDates();

            function timedRefresh(alertTimeout) {
                setTimeout(showAlert, alertTimeout);
            }

            function refreshContent(updateTimeout) {
                setTimeout(updateContent, updateTimeout);
            }

            //update date to human using moment js
            function updateDates() {
                var updated = $('.updated-at');
                updated.each(function () {
                    var d = moment();
                    var a = moment($(this).attr('data-updated-at'));
                    var human = d.to(a);
                    $(this).text(human);
                });

                //store latest updated date into loacal storage
                if(typeof(Storage) !== "undefined") {
                    localStorage.lastUpdatedTime = moment().format("dddd, MMMM Do YYYY, h:mm:ss a");
                    $('#last-updated-date-time').html(localStorage.lastUpdatedTime)
                }

            }

            function desktopAlerts($item) {
                if($item.desktopAlert == true){
                    document.getElementById("myAudio").play();
                }
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

            //update content functionality
            function updateContent() {
                $.ajax({
                    url: "{{url('items/updateContent')}}",
                    type: "POST",
                    data: { _token: "{{csrf_token()}}"},
                    dataType: "json",
                    success: function (data, textStatus, jqXHR) {
//                        console.log(data);
//                        return;
                        if (!$.isEmptyObject(data)) {
                            var stock = data.stock;
                            var price = data.price;

                        if (!$.isEmptyObject(stock)) {
                            if (stock.length > 1) {
                                stock.forEach(function (element, index, array) {
                                    if (!$.isEmptyObject(element)) {
                                        $('#example').find(".stock." + element[0].id).html(element[0].newValue);
                                        if (element[0].updated == true) {
                                            $('#example').find(".updated-at." + element[0].id).attr('data-updated-at', element[0].lastUpdated.date);
                                        }
                                        desktopAlerts(element[0]);
                                    }
                                });
                            } else {
                                $('#example').find(".stock." + stock[0].id).html(stock[0].newValue);
                                if (stock[0].updated == true) {
                                    $('#example').find(".updated-at." + stock[0].id).attr('data-updated-at', stock[0].lastUpdated.date);
                                }

                                desktopAlerts(stock[0]);
                            }
                        }
                        if (!$.isEmptyObject(price)) {
                            if (price.length > 1) {
                                price.forEach(function (element, index, array) {
                                    if (!$.isEmptyObject(element)) {
                                        $('#example').find(".price." + element[0].id).html('$' + element[0].newValue);
                                        if (element[0].updated == true) {
                                            $('#example').find(".updated-at." + element[0].id).attr('data-updated-at', element[0].lastUpdated.date);
                                        }
                                        desktopAlerts(element[0]);
                                    }
                                });
                            } else {
                                $('#example').find(".price." + price[0].id).html('$' + price[0].newValue);
                                if (price[0].updated == true) {
                                    $('#example').find(".updated-at." + price[0].id).attr('data-updated-at', price[0].lastUpdated.date);
                                }
                                desktopAlerts(price[0]);
                            }
                        }

                            updateDates();
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

            //modal for price and stock history
            $('.showPrice, .showStock').on('click', function (e) {
                var type = '';
                if($(this).hasClass('price')){
                    type = 'price';
                }else{
                    type = 'stock';
                }
                var id = $(this).attr('data-id');
                var itemID = $(this).attr('data-item-id');
                $.ajax({
                    url: "/history/" + id,
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{csrf_token()}}",
                        type: type
                    },
                    dataType: "html",
                    success: function (data, textStatus, jqXHR) {
                        if(type == 'price') {
                            $('#modalTitle').html('Price history for <strong>' + itemID + '</strong> item!');
                            $('#modalContent').html(data);
                        }
                        if(type == 'stock'){
                            $('#modalTitle').html('Stock history for <strong>' + itemID + '</strong> item!');
                            $('#modalContent').html(data);
                        }
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
