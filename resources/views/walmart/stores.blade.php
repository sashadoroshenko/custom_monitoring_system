@extends('layouts.app')

@section('content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Stores</div>

                    <div class="panel-body">
                        {!! Form::open(['url' => 'home/stores', 'class' => 'form-horizontal'])!!}

                        <div class="form-group {{ $errors->has('lat') ? 'has-error' : ''}}">
                            {!! Form::label('lat', 'Lat: ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::text('lat', request()->input('lat', null), ['class' => 'form-control']) !!}
                                {!! $errors->first('lat', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('lon') ? 'has-error' : ''}}">
                            {!! Form::label('lon', 'Lon: ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::text('lon', request()->input('lon', null), ['class' => 'form-control']) !!}
                                {!! $errors->first('lon', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('zip') ? 'has-error' : ''}}">
                            {!! Form::label('zip', 'Zip: ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::text('zip', request()->input('zip', null), ['class' => 'form-control']) !!}
                                {!! $errors->first('zip', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('city') ? 'has-error' : ''}}">
                            {!! Form::label('city', 'City: ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::text('city', request()->input('city', null), ['class' => 'form-control']) !!}
                                {!! $errors->first('city', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-3">
                                {!! Form::submit('Search', ['class' => 'btn btn-primary form-control']) !!}
                            </div>
                        </div>
                        {!! Form::close() !!}

                        @if(isset($response))
                            <pre class="pre-scrollable">

                            <?php
                                print_r($response);
                                ?>
                            </pre>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection