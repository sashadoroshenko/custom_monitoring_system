@extends('layouts.app')

@section('content')
    <link href="{{ asset('/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        h1 {
            font-size: 1.5em;
            margin-top: 0px;
        }

        .stack {
            font-size: 0.85em;
        }

        .date {
            min-width: 75px;
        }

        .text {
            word-break: break-all;
        }

        a.llv-active {
            z-index: 2;
            background-color: #f5f5f5;
            border-color: #777;
        }
        .dataTables_filter{
            float: right;
        }
    </style>

    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <h1><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Laravel Log Viewer</h1>
            {{--<p class="text-muted"><i>by Rap2h</i></p>--}}
            <div class="list-group">
                @foreach($files as $file)
                    <a href="?l={{ base64_encode($file) }}" class="list-group-item @if ($current_file == $file) llv-active @endif">
                        {{$file}}
                    </a>
                @endforeach
            </div>
        </div>
        @if(count($logs) > 0)
            <div class="col-sm-9">
                <a href="?dl={{ base64_encode($current_file) }}" class="btn btn-success">
                    <span class="glyphicon glyphicon-download-alt"></span>
                    Download file
                </a>
                /
                <a id="delete-log" href="?del={{ base64_encode($current_file) }}" class="btn btn-danger">
                    <span class="glyphicon glyphicon-trash"></span>
                    Delete file
                </a>
                <div class="clearfix" style="padding-bottom: 10px;"></div>
            </div>
        @endif
        <div class="col-sm-9 col-md-10 table-container">
            @if ($logs === null)
                <div>
                    Log file >50M, please download it.
                </div>
            @else
                <table id="table-log" class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Level</th>
                        <th>Date</th>
                        <th>Content</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($logs as $key => $log)
                        <tr>
                            <td class="text-center text-{{$log['level_class']}}">
                                <span class="glyphicon glyphicon-{{$log['level_img']}}-sign" aria-hidden="true"></span>
                                &nbsp;{{$log['level']}}
                            </td>
                            <td class="date created-at" data-created-at="{{ showCurrentDateTime($log['date']) }}">{{ showCurrentDateTime($log['date']) }}</td>
                            <td class="text">
                                @if ($log['stack'])
                                    <a class="pull-right expand btn btn-default btn-xs" data-display="stack{{$key}}">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </a>
                                @endif
                                {{$log['text']}}
                                @if (isset($log['in_file']))
                                    <br/>
                                    {{$log['in_file']}}
                                @endif
                                @if ($log['stack'])
                                    <div class="stack" id="stack{{$key}}" style="display: none; white-space: pre-wrap;">
                                        {{ trim($log['stack']) }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset('/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $('#table-log').DataTable({
                "pageLength": 50
            });
            $('.table-container').on('click', '.expand', function () {
                $('#' + $(this).data('display')).toggle();
            });
            $('#delete-log').click(function () {
                return confirm('Are you sure?');
            });
        });
    </script>
@endsection
