@extends('layouts.app')

@section('content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Reviews</div>

                    <div class="panel-body">
                        {!! Form::open(['url' => 'home/reviews', 'class' => 'form-horizontal'])!!}

                        <div class="form-group {{ $errors->has('id') ? 'has-error' : ''}}">
                            {!! Form::label('id', 'Item ID: ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::text('id', request()->input('id', null), ['class' => 'form-control', 'placeholder' => 'required', 'required']) !!}
                                {!! $errors->first('id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('lsPublisherId') ? 'has-error' : ''}}">
                            {!! Form::label('lsPublisherId', 'lsPublisherId: ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::text('lsPublisherId', request()->input('lsPublisherId', null), ['class' => 'form-control']) !!}
                                {!! $errors->first('lsPublisherId', '<p class="help-block">:message</p>') !!}
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