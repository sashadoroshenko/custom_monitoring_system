<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{asset('/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image"/>
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
        @endif

    <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat">
                    <i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        @if (! Auth::guest())
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                <li class="@if(request()->is('dashboard')) active @endif"><a href="{{url('dashboard')}}"><i class='fa fa-dashboard'></i> <span>Dashboard</span></a></li>
                <!-- Optionally, you can add icons to the links -->
                <li class="treeview @if(request()->is('profile') || request()->is('walmart-api-keys') || request()->is('logs')) active @endif">
                    <a href="#"><i class='fa fa-cogs'></i> <span>Settings</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li class="@if(request()->is('profile')) active @endif"><a href="{{url('profile')}}"><i class='fa fa-link'></i> <span>Profile</span></a></li>
                        <li class="@if(request()->is('walmart-api-keys')) active @endif"><a href="{{url('walmart-api-keys')}}"><i class='fa fa-link'></i><span>Walmart api keys</span></a></li>
                        <li class="@if(request()->is('logs')) active @endif"><a href="{{url('logs')}}"><i class='fa fa-link'></i> <span>Logs</span></a></li>
                    </ul>
                </li>
                <li class="@if(request()->is('items')) active @endif"><a href="{{url('items')}}"><i class='fa fa-link'></i> <span>Items</span></a></li>

            </ul><!-- /.sidebar-menu -->
        @endif
    </section>
    <!-- /.sidebar -->
</aside>
