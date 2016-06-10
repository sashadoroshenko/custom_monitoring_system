@extends('layouts.app')

@section('content')
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th> Title</th>
                <th> Content</th>
                <th> Contact Details</th>
                <th> Status</th>
                <th> Last Update</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $notification->title }}</td>
                <td>{{ $notification->content }}</td>
                <td>{{ $notification->contact_details }}</td>
                <td>
                    @if($notification->status)
                        <span class="label label-success"> Unread </span>
                    @else
                        <span class="label label-danger"> Read </span>
                    @endif
                </td>
                <td>{{ showCurrentDateTime($notification->updated_at) }}</td>
            </tr>
            </tbody>
        </table>
    </div>

@endsection
