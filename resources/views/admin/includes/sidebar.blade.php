<?php

function setActiveMenu($route) {
    return (Request::is($route) || Request::is($route . '/*')) ? 'active' : '';
}
?>

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                        <img alt="image" class="img-thumbnail"  src="{{asset('/admins/images/logo.png')}}" />
                    </span>
                </div>
                <div class="logo-element">
                    Ethiopay
                </div>
            </li>
            <li class="{{ setActiveMenu('admin/dashboard') }}">
                <a href="{{ url('admin/dashboard') }}">
                    <i class="fa fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li class="{{ setActiveMenu('admin/service-types') }}">
                <a href="{{ url('admin/service-types') }}">
                    <i class="fa fa-university"></i>
                    <span class="nav-label">Service Types</span>
                </a>
            </li>
            <li class="{{ setActiveMenu('admin/service-providers') }}">
                <a href="{{ url('admin/service-providers') }}">
                    <i class="fa fa-sitemap"></i>
                    <span class="nav-label">Service Providers</span>
                </a>
            </li>
            <li class="{{ setActiveMenu('admin/users') }}">
                <a href="{{ url('admin/users') }}">
                    <i class="fa fa-user"></i>
                    <span class="nav-label">Users</span>
                </a>
            </li>
            <li class="{{ setActiveMenu('admin/settings') }}">
                <a href="{{ url('admin/settings') }}">
                    <i class="fa fa-cog"></i>
                    <span class="nav-label">Settings</span>
                </a>
            </li>
            <li class="{{ setActiveMenu('admin/tickets') }}">
                <a href="{{ url('admin/tickets') }}">
                    <i class="fa fa-ticket"></i>
                    <span class="nav-label">Support Tickets</span>
                </a>
            </li>
            <li class="{{ setActiveMenu('admin/transaction') }}">
                <a href="{{ url('admin/transaction') }}">
                    <i class="fa fa-dollar"></i>
                    <span class="nav-label">Transaction</span>
                </a>
            </li>            
            <li class="{{ setActiveMenu('admin/uid-lookup') }}">
                <a href="{{ url('admin/uid-lookup') }}">
                    <i class="fa fa-cog"></i>
                    <span class="nav-label">UID Lookup</span>
                </a>
            </li>
            <li class="{{ setActiveMenu('admin/uid-missing') }}">
                <a href="{{ url('admin/uid-missing') }}">
                    <i class="fa fa-cog"></i>
                    <span class="nav-label">UID Missing</span>
                </a>
            </li>
            <li class="{{ setActiveMenu('admin/sms-email-message') }}">
                <a href="{{ url('admin/sms-email-message') }}">
                    <i class="fa fa-cog"></i>
                    <span class="nav-label">SMS-Email Message</span>
                </a>
            </li>
            <li class="{{ setActiveMenu('admin/admin-user-message') }}">
                <a href="{{ url('admin/admin-user-message') }}">
                    <i class="fa fa-commenting"></i>
                    <span class="nav-label">Admin-User Message</span>
                </a>
            </li>
            @if(Auth::guard('admin')->user()->user_type == 'superadmin')
            <li class="{{ setActiveMenu('admin/activity-log') }}">
                <a href="{{ url('admin/activity-log') }}">
                    <i class="fa fa-file"></i>
                    <span class="nav-label">Activity Log</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</nav>


