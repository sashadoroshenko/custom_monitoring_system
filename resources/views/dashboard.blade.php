@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{$items}}</h3>
                    <p>Total Items</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{url('items')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $logs_count }}</h3>
                    <p>Total Logs</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{url('logs')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{$users_count}}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{url('profile')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{$keys_count}}</h3>
                    <p>Total Keys</p>
                </div>
                <div class="icon">
                    <i class="fa fa-unlock-alt"></i>
                </div>
                <a href="{{url('walmart-api-keys')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div><!-- ./col -->
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Latest Orders</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($last_list as $last)
                                    <tr>
                                        <td><a href="{{$last->url}}" target="_blank">{{$last->itemID}}</a></td>
                                        <td>{{$last->title}}</td>
                                        <td><span class="label label-success">${{$last->prices[0]->price}}</span></td>
                                        <td><span class="label @if($last->stocks[0]->stock == "Not available") label-warning @else label-success @endif">{{$last->stocks[0]->stock}}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Recently Added Products</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        @if(count($product_list) > 0)
                            @foreach($product_list as $item)
                                <li class="item">
                                    <div class="product-img">
                                        <img src="/img/default-50x50.gif" alt="Product Image">
                                    </div>
                                    <div class="product-info">
                                        <a href="{{ $item->url }}" class="product-title" target="_blank">{{ $item->title }}
                                            <span class="label label-warning pull-right">${{ $item->prices()->orderBy('status', 1)->first()->price }}</span>
                                        </a>
                                <span class="product-description">
                                  {{ $item->title }}
                                </span>
                                    </div>
                                </li><!-- /.item -->
                            @endforeach
                        @endif
                    </ul>
                </div><!-- /.box-body -->
                <div class="box-footer text-center">
                    <a href="{{ url('items') }}" class="uppercase">View All Products</a>
                </div><!-- /.box-footer -->
            </div>
        </div>
    </div>

@endsection