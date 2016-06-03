<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">

</head>
<body>
<div class="container-fluid">
    <h1>Hello!</h1>
    @if($type == 'price')
        <ul class="list-inline">
            <li><strong>{{ $itemID }}</strong> change price. Old Price {{ $oldValue }} new price {{ $newValue }}</li>
        </ul>
    @endif
    <hr>
    @if($type == 'stock')
        <ul class="list-inline">
            <li><strong>{{ $itemID }}</strong> change stock. Old Stock {{ $oldValue }} new stock {{ $newValue }}</li>
        </ul>
    @endif
    <hr>
    <h3></h3>
    <a href="{{$url}}">{{$title}}</a>
</div>

<!-- JavaScripts -->
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>

</body>
</html>
