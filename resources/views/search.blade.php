@extends('layouts.app')

@section('content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Search</div>

                    <div class="panel-body">
                        {!! Form::open(['url' => 'home/search', 'class' => 'form-horizontal'])!!}

                        <div class="form-group {{ $errors->has('query') ? 'has-error' : ''}}">
                            {!! Form::label('query', 'Query: ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::text('query', request()->input('query', null), ['class' => 'form-control', 'placeholder' => 'required', 'required']) !!}
                                {!! $errors->first('query', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('categoryId') ? 'has-error' : ''}}">
                            {!! Form::label('categoryId', 'CategoryId: ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::text('categoryId', request()->input('categoryId', null), ['class' => 'form-control']) !!}
                                {!! $errors->first('categoryId', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('facet') ? 'has-error' : ''}}">
                            {!! Form::label('facet', 'Facet: ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::select('facet', ['off' => 'Off', 'on' => 'On'], request()->input('facet', ['off' => 'Off']), ['class' => 'form-control']) !!}
                                {!! $errors->first('facet', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('filter') ? 'has-error' : ''}}">
                            {!! Form::label('filter', 'Filter: ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::text('filter', request()->input('filter', null), ['class' => 'form-control']) !!}
                                <p class="help-block">Facet field must be "On" for example brand:Samsung </p>
                                {!! $errors->first('filter', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('range') ? 'has-error' : ''}}">
                            {!! Form::label('range', 'Range: ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::text('range', request()->input('range', null), ['class' => 'form-control']) !!}
                                <p class="help-block">Facet field must be "On" for example price:[150 TO 250]</p>
                                {!! $errors->first('range', '<p class="help-block">:message</p>') !!}
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