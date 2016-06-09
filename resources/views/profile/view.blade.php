@extends('layouts.app')

@section('content')

    <h1>User {{ $profile->name }}</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <tbody>
            <tr>
                <th>ID.</th>
                <td>{{ $profile->id }}</td>
            </tr>
            <tr>
                <th> User name </th>
                <td> {{ $profile->name }} </td>
            </tr>
            <tr>
                <th> User email </th>
                <td> {{ $profile->email }} </td>
            </tr>
            <tr>
                <th> User phone </th>
                <td> {{ $profile->phone ?: "+000000000" }} </td>
            </tr>
            <tr>
                <th> User location </th>
                <td> {{ $profile->location ?: "UTC" }} </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">
                    <a href="{{ url('profile/edit') }}" class="btn btn-primary btn-xs" title="Edit User data">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"/>
                    </a>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
@endsection