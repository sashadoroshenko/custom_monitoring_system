@extends('layouts.app')

@section('content')
    <link href="{{ asset('/css/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <h1>
        <a class="btn btn-warning read-all" href="#">Make all us read</a>
    </h1>
    <div class="table">
        <table id="example" class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>S.No</th>
                <th> Title</th>
                <th> Content</th>
                <th> Contact Details</th>
                <th> Status</th>
                <th> Last Update</th>
            </tr>
            </thead>
            <tbody>
            {{-- */$x=0;/* --}}
            @foreach($notifications as $notification)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    <td>{{ $notification->title }}</td>
                    <td>{{ $notification->content }}</td>
                    <td>{{ $notification->contact_details }}</td>
                    <td class="text-center">
                        @if($notification->status)
                            <span class="label label-success status"> Unread </span>
                        @else
                            <span class="label label-danger"> Read </span>
                        @endif
                    </td>
                    <td>{{ showCurrentDateTime($notification->updated_at) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/dataTables.bootstrap.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#example').DataTable();
            $('.read-all').on('click', function (e) {
                e.preventDefault();
                makeAllUsReaded();
            })
        });

        function makeAllUsReaded() {
            $.ajax({
                url: "{{ request()->url() . '/read-all'}}",
                type: "POST",
                data: { _token: "{{csrf_token()}}"},
                dataType: "json",
                success: function (data, textStatus, jqXHR) {
                    if(data.status = 200) {
                        var type = data.type;
                        $('.status').removeClass('label-success').addClass('label-danger').text('Read');
                        $('.notification-unread-' + type + '-menu').children().remove();
                        $('.notification-unread-' + type + '-count').text(0);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error');
                    console.log(jqXHR);

                }
            });

        }
    </script>
@endsection
