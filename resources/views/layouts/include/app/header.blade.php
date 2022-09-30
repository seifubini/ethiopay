<header class="innerpage_header">
    <div class="header_top">
        <div class="container">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/logo.png') }}">
                </a>
            </div>
            <div class="menu">
                <nav class="navbar navbar-inverse">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false"
                                aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="{{ url('transaction') }}" class="{{ (in_array(Route::currentRouteName(), array('transaction.index', 'transaction.show'))) ? 'active' : '' }}">Transaction</a>
                            </li>
                            <li>
                                <a href="{{ url('home') }}" class="{{ (in_array(Route::currentRouteName(), array('home', 'home.transaction.show'))) ? 'active' : '' }}">Home</a>
                            </li>

                            <li>
                                <a href="{{ url('tickets') }}" class="{{ (in_array(Route::currentRouteName(), array('tickets.index', 'tickets.create', 'tickets.show'))) ? 'active' : '' }}">Support Tickets</a>
                            </li>
                            <!--                            <li>
                                                            <a href="#">TRANSACTIONS</a>
                                                        </li>-->
                            <li>
                                <a href="{{ route('payment-methods.index') }}" class="{{ (in_array(Route::currentRouteName(), array('payment-methods.index', 'payment-methods.create', 'payment-methods.edit', 'payment-methods.show'))) ? 'active' : '' }}">WALLET</a>
                            </li>

                            <!--                            <li>
                                                            <a href="#">SUPPORT</a>
                                                        </li>-->
                            <!--                            <li>
                                                            <a href="#">MY ACCOUNT</a>
                                                        </li>-->
                            <li>
                                <a href="{{ url('profile') }}" class="{{ (Route::currentRouteName() == 'profile') ? 'active' : '' }}">MY ACCOUNT</a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="dropdown header_profile">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <div class="prouserimg">
                            <img id="headerSectionProfilePicture" src="{{ auth()->guard('web')->user()->profile_picture_small }}" alt="user" class="user-mage">
                        </div>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
