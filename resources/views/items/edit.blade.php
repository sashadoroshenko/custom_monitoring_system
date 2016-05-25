@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Edit Item {{ $item->id }}</h1>

        {!! Form::model($item, [
            'method' => 'PATCH',
            'url' => ['/items', $item->id],
            'class' => 'form-horizontal'
        ]) !!}

        <div class="form-group {{ $errors->has('parentItemId') ? 'has-error' : ''}}">
            {!! Form::label('parentItemId', trans('items.parentItemId'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('parentItemId', null, ['class' => 'form-control']) !!}
                {!! $errors->first('parentItemId', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
            {!! Form::label('name', trans('items.name'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('salePrice') ? 'has-error' : ''}}">
            {!! Form::label('salePrice', trans('items.salePrice'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('salePrice', null, ['class' => 'form-control']) !!}
                {!! $errors->first('salePrice', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('upc') ? 'has-error' : ''}}">
            {!! Form::label('upc', trans('items.upc'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('upc', null, ['class' => 'form-control']) !!}
                {!! $errors->first('upc', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('categoryPath') ? 'has-error' : ''}}">
            {!! Form::label('categoryPath', trans('items.categoryPath'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('categoryPath', null, ['class' => 'form-control']) !!}
                {!! $errors->first('categoryPath', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('shortDescription') ? 'has-error' : ''}}">
            {!! Form::label('shortDescription', trans('items.shortDescription'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::textarea('shortDescription', null, ['class' => 'form-control']) !!}
                {!! $errors->first('shortDescription', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('longDescription') ? 'has-error' : ''}}">
            {!! Form::label('longDescription', trans('items.longDescription'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::textarea('longDescription', null, ['class' => 'form-control']) !!}
                {!! $errors->first('longDescription', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('brandName') ? 'has-error' : ''}}">
            {!! Form::label('brandName', trans('items.brandName'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('brandName', null, ['class' => 'form-control']) !!}
                {!! $errors->first('brandName', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('thumbnailImage') ? 'has-error' : ''}}">
            {!! Form::label('thumbnailImage', trans('items.thumbnailImage'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('thumbnailImage', null, ['class' => 'form-control']) !!}
                {!! $errors->first('thumbnailImage', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('mediumImage') ? 'has-error' : ''}}">
            {!! Form::label('mediumImage', trans('items.mediumImage'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('mediumImage', null, ['class' => 'form-control']) !!}
                {!! $errors->first('mediumImage', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('largeImage') ? 'has-error' : ''}}">
            {!! Form::label('largeImage', trans('items.largeImage'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('largeImage', null, ['class' => 'form-control']) !!}
                {!! $errors->first('largeImage', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('productTrackingUrl') ? 'has-error' : ''}}">
            {!! Form::label('productTrackingUrl', trans('items.productTrackingUrl'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('productTrackingUrl', null, ['class' => 'form-control']) !!}
                {!! $errors->first('productTrackingUrl', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('ninetySevenCentShipping') ? 'has-error' : ''}}">
            {!! Form::label('ninetySevenCentShipping', trans('items.ninetySevenCentShipping'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('ninetySevenCentShipping', null, ['class' => 'form-control']) !!}
                {!! $errors->first('ninetySevenCentShipping', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('standardShipRate') ? 'has-error' : ''}}">
            {!! Form::label('standardShipRate', trans('items.standardShipRate'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('standardShipRate', null, ['class' => 'form-control']) !!}
                {!! $errors->first('standardShipRate', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('size') ? 'has-error' : ''}}">
            {!! Form::label('size', trans('items.size'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('size', null, ['class' => 'form-control']) !!}
                {!! $errors->first('size', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('color') ? 'has-error' : ''}}">
            {!! Form::label('color', trans('items.color'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('color', null, ['class' => 'form-control']) !!}
                {!! $errors->first('color', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('marketplace') ? 'has-error' : ''}}">
            {!! Form::label('marketplace', trans('items.marketplace'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('marketplace', null, ['class' => 'form-control']) !!}
                {!! $errors->first('marketplace', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('shipToStore') ? 'has-error' : ''}}">
            {!! Form::label('shipToStore', trans('items.shipToStore'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('shipToStore', null, ['class' => 'form-control']) !!}
                {!! $errors->first('shipToStore', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('freeShipToStore') ? 'has-error' : ''}}">
            {!! Form::label('freeShipToStore', trans('items.freeShipToStore'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('freeShipToStore', null, ['class' => 'form-control']) !!}
                {!! $errors->first('freeShipToStore', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('productUrl') ? 'has-error' : ''}}">
            {!! Form::label('productUrl', trans('items.productUrl'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('productUrl', null, ['class' => 'form-control']) !!}
                {!! $errors->first('productUrl', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('variants') ? 'has-error' : ''}}">
            {!! Form::label('variants', trans('items.variants'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('variants', null, ['class' => 'form-control']) !!}
                {!! $errors->first('variants', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('categoryNode') ? 'has-error' : ''}}">
            {!! Form::label('categoryNode', trans('items.categoryNode'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('categoryNode', null, ['class' => 'form-control']) !!}
                {!! $errors->first('categoryNode', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('bundle') ? 'has-error' : ''}}">
            {!! Form::label('bundle', trans('items.bundle'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('bundle', null, ['class' => 'form-control']) !!}
                {!! $errors->first('bundle', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('clearance') ? 'has-error' : ''}}">
            {!! Form::label('clearance', trans('items.clearance'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('clearance', null, ['class' => 'form-control']) !!}
                {!! $errors->first('clearance', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('preOrder') ? 'has-error' : ''}}">
            {!! Form::label('preOrder', trans('items.preOrder'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('preOrder', null, ['class' => 'form-control']) !!}
                {!! $errors->first('preOrder', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('stock') ? 'has-error' : ''}}">
            {!! Form::label('stock', trans('items.stock'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('stock', null, ['class' => 'form-control']) !!}
                {!! $errors->first('stock', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('attribute') ? 'has-error' : ''}}">
            {!! Form::label('attribute', trans('items.attribute'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('attribute', null, ['class' => 'form-control']) !!}
                {!! $errors->first('attribute', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('gender') ? 'has-error' : ''}}">
            {!! Form::label('gender', trans('items.gender'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('gender', null, ['class' => 'form-control']) !!}
                {!! $errors->first('gender', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('addToCartUrl') ? 'has-error' : ''}}">
            {!! Form::label('addToCartUrl', trans('items.addToCartUrl'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('addToCartUrl', null, ['class' => 'form-control']) !!}
                {!! $errors->first('addToCartUrl', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('affiliateAddToCartUrl') ? 'has-error' : ''}}">
            {!! Form::label('affiliateAddToCartUrl', trans('items.affiliateAddToCartUrl'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('affiliateAddToCartUrl', null, ['class' => 'form-control']) !!}
                {!! $errors->first('affiliateAddToCartUrl', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('freeShippingOver50Dollars') ? 'has-error' : ''}}">
            {!! Form::label('freeShippingOver50Dollars', trans('items.freeShippingOver50Dollars'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('freeShippingOver50Dollars', null, ['class' => 'form-control']) !!}
                {!! $errors->first('freeShippingOver50Dollars', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('maxItemsInOrder') ? 'has-error' : ''}}">
            {!! Form::label('maxItemsInOrder', trans('items.maxItemsInOrder'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('maxItemsInOrder', null, ['class' => 'form-control']) !!}
                {!! $errors->first('maxItemsInOrder', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('giftOptions') ? 'has-error' : ''}}">
            {!! Form::label('giftOptions', trans('items.giftOptions'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('giftOptions', null, ['class' => 'form-control']) !!}
                {!! $errors->first('giftOptions', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="form-group {{ $errors->has('availableOnline') ? 'has-error' : ''}}">
            {!! Form::label('availableOnline', trans('items.availableOnline'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-6">
                {!! Form::text('availableOnline', null, ['class' => 'form-control']) !!}
                {!! $errors->first('availableOnline', '<p class="help-block">:message</p>') !!}
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-3">
                {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
            </div>
        </div>
        {!! Form::close() !!}

        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

    </div>
@endsection