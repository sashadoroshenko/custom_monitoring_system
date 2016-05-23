@extends('layouts.app')

@section('content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Paginate</div>

                    <div class="panel-body">
                        <pre class="pre-scrollable">
                            <?php
                            print_r($response);
                            ?>
                        </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection