<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ url('/dashboard') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><i class="fa fa-home"></i></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Admin</b> </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="{{ url('/dashboard') }}" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-mobile"></i>
                        <span class="label label-success notification-unread-phone-count">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have <strong class="notification-unread-phone-count">0</strong> phone notifications</li>
                        <li>
                            <!-- inner menu: contains the messages -->
                            <ul class="menu notification-unread-phone-menu">
                            </ul><!-- /.menu -->
                        </li>
                        <li class="footer"><a href="{{url('notifications/phones')}}">View all</a></li>
                    </ul>
                </li><!-- /.messages-menu -->

                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success notification-unread-email-count">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have <strong class="notification-unread-email-count">0</strong> message notifications</li>
                        <li>
                            <!-- inner menu: contains the messages -->
                            <ul class="menu notification-unread-email-menu">
                            </ul><!-- /.menu -->
                        </li>
                        <li class="footer"><a href="{{url('notifications/emails')}}">View all</a></li>
                    </ul>
                </li><!-- /.messages-menu -->

                <!-- Notifications Menu -->
                <li class="dropdown messages-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-money"></i>
                        <span class="label label-warning notification-unread-price-count">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have <strong class="notification-unread-price-count">0</strong> price notifications</li>
                        <li>
                            <!-- Inner Menu: contains the notifications -->
                            <ul class="menu notification-unread-price-menu">
                            </ul>
                        </li>
                        <li class="footer"><a href="{{url('notifications/prices')}}">View all</a></li>
                    </ul>
                </li>
                <!-- Tasks Menu -->
                <li class="dropdown messages-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-stack-exchange"></i>
                        <span class="label label-danger notification-unread-stock-count">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have <strong class="notification-unread-stock-count">0</strong> stock notifications</li>
                        <li>
                            <!-- Inner menu: contains the tasks -->
                            <ul class="menu notification-unread-stock-menu">
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="{{url('notifications/stocks')}}">View all</a>
                        </li>
                    </ul>
                </li>
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
                                <p>
                                    {{ Auth::user()->name }}
                                    <small>Member since {{ showCurrentDateTime(Auth::user()->created_at)->diffForHumans() }}</small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            {{--<li class="user-body">--}}
                                {{--<div class="col-xs-4 text-center">--}}
                                    {{--<a href="#">Followers</a>--}}
                                {{--</div>--}}
                                {{--<div class="col-xs-4 text-center">--}}
                                    {{--<a href="#">Sales</a>--}}
                                {{--</div>--}}
                                {{--<div class="col-xs-4 text-center">--}}
                                    {{--<a href="#">Friends</a>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{url('profile')}}" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('/logout') }}" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
</header>