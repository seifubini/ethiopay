<div class="row border-bottom">
    <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span m-r-sm text-muted welcome-message">Welcome {{Auth::guard('admin')->user()->name}}</span>
            </li> 
            
            <li>
                <a href="{{ url('admin/logout') }}">
                    Logout
                </a>

                {{-- <form id="logout-form" action="{{ url('admin/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form> --}}
            </li>
        </ul>

    </nav>
</div>

