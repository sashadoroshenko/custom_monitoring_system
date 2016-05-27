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

<div class="form-group {{ $errors->has('userID') ? 'has-error' : ''}}">
    {!! Form::label('userID', 'User ID', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        <div class="input-group">
            {!! Form::text('userID', null, ['class' => 'form-control', 'placeholder' => 'Enter user ID']) !!}
            <span class="input-group-addon" id="basic-addon2"><i class="glyphicon glyphicon-user"></i></span>
        </div>
        {!! $errors->first('userID', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('price') ? 'has-error' : ''}}">
    {!! Form::label('price', 'Price', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        <div class="input-group">
            {!! Form::text('price', null, ['class' => 'form-control price', 'placeholder' => 'Item price', 'readonly']) !!}
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

<div class="form-group">
    {!! Form::label('alert', 'Alert System', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-6">
        <div class="input-group">
            <div class="checkbox">
                <label>{!! Form::checkbox('alert_desktop', 'true', null) !!} Desktop</label>
            </div>
            <div class="checkbox">
                <label>{!! Form::checkbox('alert_email', 'true', null) !!} Email</label>
            </div>
            <div class="checkbox disabled">
                <label>{!! Form::checkbox('alert_sms', 'true', null) !!} SMS</label>
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
                            if(jqXHR.status === 200){
                                $('.title').val(data.name);
                                $('.stock').val(data.stock);
                                $('.price').val(data.salePrice);
                                if(data.stock === "Available"){
                                    $('.stock-icon').addClass("glyphicon-ok").removeClass("glyphicon-remove").removeClass("glyphicon-tag")
                                }else{
                                    $('.stock-icon').addClass("glyphicon-remove").removeClass("glyphicon-ok").removeClass("glyphicon-tag")
                                }
                                console.log(data);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR)
                        }
                    });
                }
            });
        });

    </script>
@endsection