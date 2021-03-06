<div class="form-group {{ $errors->has('itemID') ? 'has-error' : ''}}">
    {!! Form::label('itemID', 'Item ID', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        <div class="input-group">
            {!! Form::text('itemID', null, ['class' => 'form-control itemID', 'placeholder' => 'Enter Item ID']) !!}
            <span class="input-group-addon" id="basic-addon2"><i class="glyphicon glyphicon-refresh"></i></span>
        </div>
        {!! $errors->first('itemID', '<p class="help-block">:message</p>') !!}
    </div>
</div>

{{--<div class="form-group {{ $errors->has('userID') ? 'has-error' : ''}}">--}}
    {{--{!! Form::label('userID', 'User ID', ['class' => 'col-sm-3 control-label']) !!}--}}
    {{--<div class="col-sm-6">--}}
        {{--<div class="input-group">--}}
            {{--{!! Form::text('userID', null, ['class' => 'form-control', 'placeholder' => 'Enter user ID']) !!}--}}
            {{--<span class="input-group-addon" id="basic-addon2"><i class="glyphicon glyphicon-user"></i></span>--}}
        {{--</div>--}}
        {{--{!! $errors->first('userID', '<p class="help-block">:message</p>') !!}--}}
    {{--</div>--}}
{{--</div>--}}

<div class="form-group {{ $errors->has('price') ? 'has-error' : ''}}">
    {!! Form::label('price', 'Price', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        <div class="input-group">
            @if(isset($item))
                {!! Form::text('price', $item->prices()->where('status', 1)->first()->price, ['class' => 'form-control price', 'placeholder' => 'Item price', 'readonly']) !!}
            @else
                {!! Form::text('price', null, ['class' => 'form-control price', 'placeholder' => 'Item price', 'readonly']) !!}
            @endif
            <span class="input-group-addon" id="basic-addon2"><i class="glyphicon glyphicon-usd"></i></span>
        </div>
        {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'Title', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        <div class="input-group">
            {!! Form::text('title', null, ['class' => 'form-control title', 'placeholder' => 'Item title', 'readonly']) !!}
            <span class="input-group-addon" id="basic-addon2"><i class="glyphicon glyphicon-tag"></i></span>
        </div>
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('stock') ? 'has-error' : ''}}">
    {!! Form::label('stock', 'Stock', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        <div class="input-group">
            {!! Form::text('stock', null, ['class' => 'form-control stock', 'placeholder' => 'Item title', 'readonly']) !!}
            <span class="input-group-addon" id="basic-addon2"><i class="glyphicon glyphicon-tag stock-icon"></i></span>
        </div>
        {!! $errors->first('stock', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('url') ? 'has-error' : ''}}">
    {!! Form::label('url', 'Item url', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        <div class="input-group">
            {!! Form::text('url', null, ['class' => 'form-control url', 'placeholder' => 'Item url', 'readonly']) !!}
            <span class="input-group-addon" id="basic-addon2"><i class="glyphicon glyphicon-tag"></i></span>
        </div>
        {!! $errors->first('url', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('alert', 'Alert System', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        <div class="input-group">
            <div class="checkbox">
                <label>{!! Form::checkbox('alert_desktop', 1, null) !!} Desktop</label>
            </div>
            <div class="checkbox">
                <label>{!! Form::checkbox('alert_email', 1, null) !!} Email</label>
            </div>
            <div class="checkbox disabled">
                <label>{!! Form::checkbox('alert_sms', 1, null) !!} SMS</label>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-3">
        {!! Form::submit($submitButton, ['class' => 'btn btn-primary form-control']) !!}
    </div>
</div>
@section('scripts')
    <script>

        $(document).ready(function () {
            var itemID = $(".itemID");
            itemID.blur(function (e) {
                if(itemID.val().length > 2) {
                    $.ajax({
                        url: "{{ url('items/items') }}",
                        type: "POST",
                        data: {
                            id: itemID.val(),
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function (data, textStatus, jqXHR) {
//                            console.log(data);
                            if(data.length != 0) {
                                data.forEach(function (value, index, array) {
//                                    console.log(value)
                                    $('.title').val(value.name);
                                    $('.url').val(value.productUrl);
                                    $('.stock').val(value.stock);
                                    $('.price').val(value.salePrice);
                                    if (value.stock === "Available") {
                                        $('.stock-icon').addClass("glyphicon-ok").removeClass("glyphicon-remove").removeClass("glyphicon-tag")
                                    } else {
                                        $('.stock-icon').addClass("glyphicon-remove").removeClass("glyphicon-ok").removeClass("glyphicon-tag")
                                    }
                                });
                            }else{
                                $('.title').val('');
                                $('.stock').val('');
                                $('.price').val('');
                                $('.stock-icon').removeClass("glyphicon-remove").removeClass("glyphicon-ok").addClass("glyphicon-tag")
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert(jqXHR.responseText);
                            console.log('error');
                            console.log(jqXHR);
                        }
                    });
                }
            });
        });

    </script>
@endsection