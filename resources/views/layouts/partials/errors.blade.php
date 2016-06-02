@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong>
        There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(auth()->check())
    @if(Session::has('flash_message'))
        <div class="alert alert-success">
            <h2>{{ Session::get('flash_message') }}</h2>
        </div>
    @endif
    <pre>
        <?php
            print_r(session()->all());
        ?>
    </pre>
@endif