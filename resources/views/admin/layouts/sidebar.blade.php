<div class="left-side sticky-left-side">

    <!--logo and iconic logo start-->
    <div class="logo">
        <a href="{{ route('admin.dashboard') }}"><img src="{{ asset('admin/images/logo.png') }}" alt=""></a>
    </div>

    <div class="logo-icon text-center">
        <a href="{{ route('admin.dashboard') }}"><img src="{{ asset('admin/images/login-logo.png') }}" alt="" height="40" width="40"></a>
    </div>
    <!--logo and iconic logo end-->

    <div class="left-side-inner">

        <!-- visible to small devices only -->
        <div class="visible-xs hidden-sm hidden-md hidden-lg">
            <div class="media logged-user">
                <img alt="" src="{{ asset('admin/images/photos/user-avatar.png') }}" class="media-object">
                <div class="media-body">
                    <h4><a href="#">John Doe</a></h4>
                    <span>"Hello There..."</span>
                </div>
            </div>

            <h5 class="left-nav-title">Account Information</h5>
            <ul class="nav nav-pills nav-stacked custom-nav">
              <li><a href="#"><i class="fa fa-user"></i> <span>Profile</span></a></li>
              <li><a href="#"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
              <li><a href="#"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>
            </ul>
        </div>

        <!--sidebar nav start-->
        <ul class="nav nav-pills nav-stacked custom-nav">
            <li class="@if(\Request::segment(2) == 'dashboard') active @endif"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>

            <li class="menu-list @if(\Request::segment(2) == 'employees') nav-active @endif"><a href="#"><i class="fa fa-users"></i> <span>Employees</span></a>
                <ul class="sub-menu-list">
                    <li class="@if(\Request::segment(2) == 'employees' && \Request::segment(3) == '') active @endif"><a href="{{ route('admin.employees.index') }}"> List </a></li>
                    <li class="@if(\Request::segment(2) == 'employees' && \Request::segment(3) == 'create') active @endif"><a href="{{ route('admin.employees.create') }}"> Create</a></li>
                </ul>
            </li>

            <li class="menu-list @if(\Request::segment(2) == 'leaves') nav-active @endif"><a href="#"><i class="fa fa-home"></i> <span>Leaves</span></a>
                <ul class="sub-menu-list">
                    <li class="@if(\Request::segment(2) == 'leaves' && \Request::segment(3) == '') active @endif"><a href="{{ route('admin.leaves.index') }}"> List </a></li>
                    {{-- <li class="@if(\Request::segment(2) == 'leaves' && \Request::segment(3) == 'create') active @endif"><a href="{{ route('admin.leaves.create') }}"> Create</a></li> --}}
                </ul>
            </li>

            <li class="menu-list @if(\Request::segment(2) == 'projects') nav-active @endif"><a href="#"><i class="fa fa-file"></i> <span>Projects</span></a>
                <ul class="sub-menu-list">
                    <li class="@if(\Request::segment(2) == 'projects' && \Request::segment(3) == '') active @endif"><a href="{{ route('admin.projects.index') }}"> List </a></li>
                    <li class="@if(\Request::segment(2) == 'projects' && \Request::segment(3) == 'create') active @endif"><a href="{{ route('admin.projects.create') }}"> Create</a></li>
                </ul>
            </li>

            <li class="menu-list @if(\Request::segment(2) == 'holidays') nav-active @endif"><a href="#"><i class="fa fa-file"></i> <span>Holidays</span></a>
                <ul class="sub-menu-list">
                    <li class="@if(\Request::segment(2) == 'holidays' && \Request::segment(3) == '') active @endif"><a href="{{ route('admin.holidays.index') }}"> List </a></li>
                    <li class="@if(\Request::segment(2) == 'holidays' && \Request::segment(3) == 'create') active @endif"><a href="{{ route('admin.holidays.create') }}"> Create</a></li>
                </ul>
            </li>

            <li class="menu-list @if(\Request::segment(2) == 'auth-devices') nav-active @endif"><a href="#"><i class="fa fa-phone"></i> <span>Auth Devices</span></a>
                <ul class="sub-menu-list">
                    <li class="@if(\Request::segment(2) == 'auth-devices' && \Request::segment(3) == '') active @endif"><a href="{{ route('admin.auth-devices.index') }}"> List </a></li>
                    <li class="@if(\Request::segment(2) == 'auth-devices' && \Request::segment(3) == 'create') active @endif"><a href="{{ route('admin.auth-devices.create') }}"> Create</a></li>
                </ul>
            </li>

            <li class="@if(\Request::segment(2) == 'reports') active @endif"><a href="{{ route('admin.reports.index') }}"><i class="fa fa-book"></i> <span>Leave Report</span></a></li>

            <li class="{{ (request()->is('admin/attendence-report-create*')) ? 'active' : '' }}"><a href="{{ route('admin.attendence') }}"><i class="fa fa-book"></i> <span>Attendence Report</span></a></li>

            <li class="@if(\Request::segment(2) == 'feedbacks') active @endif"><a href="{{ route('admin.feedbacks.get') }}"><i class="fa fa-comments-o"></i> <span>Feedback</span></a></li>

            <li class="@if(\Request::segment(2) == 'tds') active @endif"><a href="{{ route('admin.tds.index') }}"><i class="fa fa-comments-o"></i> <span>Tds / Salary</span></a></li>
        </ul>
        <!--sidebar nav end-->

    </div>
</div>