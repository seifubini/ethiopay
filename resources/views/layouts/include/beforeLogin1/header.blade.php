<header>
    <div class="header_top">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="logo">
                        <a href="{{ url('/') }}"><img src="{{ asset('img/logo.png') }}"></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="menu">
                        <nav class="navbar navbar-inverse">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <div id="navbar" class="navbar-collapse collapse">
                                <ul class="nav navbar-nav">
                                    <li><a href="{{ url('register') }}">Pay with Ethiopay</a></li>
                                    <li><a href="{{ url('/') }}#whyChooseEthiopaySection">Why Ethiopay</a></li>
                                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Products <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            @foreach ($viewShareData["serviceTypes"] as $serviceType)
                                                @if(count($serviceType->serviceProvidersData))
                                                    <li><a href="{{ url("pay-bill/service-type/".$serviceType->id) }}">{{ $serviceType->service_name }}</a></li>
                                                @else
                                                    <li title="Coming Soon"><a href="javascript:void(0)" class="disableClick">{{ $serviceType->service_name }}</a></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                    <!--<li><a href="{{ url('/') }}#aboutUsSection">About</a></li>-->
                                    <li><a href="{{ url('more-about-us') }}#thirdSection" class="aboutUs">About</a></li>
                                    <li><a href="{{ route('contactUs') }}">Contact</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>