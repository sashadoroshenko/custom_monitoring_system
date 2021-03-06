<!DOCTYPE html>
<html lang="en">

@section('htmlheader')
    @include('layouts.partials.htmlheader')
@show
<style>
    .navbar-nav > .messages-menu > .dropdown-menu > li .menu > li > a{
        white-space: inherit;
    }
    .navbar-nav > .messages-menu > .dropdown-menu > li .menu {
        max-height: 215px;
    }
    .navbar-nav > .messages-menu > .dropdown-menu > li .menu > li > a > h4 {
        padding-top: 15px;
    }
</style>
<body class="skin-blue sidebar-mini">
<div class="wrapper">

    @include('layouts.partials.mainheader')

    @include('layouts.partials.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        @if(auth()->check())
            @include('layouts.partials.message')
        @endif

        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            @yield('content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    @include('layouts.partials.footer')

</div><!-- ./wrapper -->

@section('main-scripts')
    @include('layouts.partials.scripts')
@show
<script>
    $(document).ready(function () {
        refreshContent(100);
    });


    function refreshContent(updateTimeout) {
        setTimeout(updateNotifications, updateTimeout);
    }

    function updateNotifications() {
        $.ajax({
            url: "{{url('notifications')}}",
            type: "POST",
{{--            data: { _token: "{{csrf_token()}}"},--}}
            dataType: "json",
            success: function (data, textStatus, jqXHR) {
                //console.log(data);return;
                if (!$.isEmptyObject(data)) {
                    var email = data.emails;
                    var price = data.prices;
                    var stock = data.stocks;
                    var phone = data.phones;

                    content(email, 'email' );
                    content(price, 'price' );
                    content(stock, 'stock' );
                    content(phone, 'phone' );
                    humanDate();
                }
                refreshContent(30000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error');
                console.log(jqXHR);

                refreshContent(60000);
            }
        });

    }

    function content(data, type ){
        if (!$.isEmptyObject(data)) {
            $('.notification-unread-' + type + '-menu').children().remove();

            var items = data.data;
            var count = data.data_count;
//            var length = data.length;
//            data.splice(10, data.length - 10);

            items.forEach(function (element, index, array) {
                if (!$.isEmptyObject(element)) {
                    $('.notification-unread-' + type + '-count').text(count);
                    $('.notification-unread-' + type + '-menu').append('' +
                            '<li>' +
                            '<a href="/notifications/' + type + '/' + element.id + '">' +
                            '<div class="pull-left">' +
                            '<img src="/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>' +
                            '</div>' +
                            '<h4>' +
                            '<span class="notification-unread-' + type + '-title">' + element.title + '</span>' +
                            '<small>' +
                            '<i class="fa fa-clock-o"></i>' +
                            '<span class="notification-unread-' + type + '-created created-at" data-created-at="' + element.created_at + '">' + element.created_at + '</span>' +
                            '</small>' +
                            '</h4>' +
                            '<p class="notification-unread-' + type + '-content" >' + element.content.substr(0, 50) + '...</p>' +
                            '</a>' +
                            '</li>');
                }
            });
        }else{
            $('.notification-unread-' + type + '-menu').children().remove();
        }


    }

    //update date to human using moment js
    function humanDate() {
        var updated = $('.created-at');
        updated.each(function () {
            var d = moment();
            var a = moment($(this).attr('data-created-at'));
            var human = d.to(a);
            $(this).text(human);
        });
    }
</script>
</body>
</html>
