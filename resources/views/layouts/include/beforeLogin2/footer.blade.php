<footer>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="logo">
                    <img src="{{ asset('img/footer_logo2.png') }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="footer_menu">
                    <nav class="navbar navbar-inverse">
                        <ul class="nav navbar-nav">
                            <li><a href="{{ url('register') }}">Pay with Ethiopay</a></li>
                            <li><a href="{{ url('/') .'#whyChooseEthiopaySection' }}">Why Ethiopay</a></li>
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
                            <li><a href="{{ route('termsAndConditions') }}">Terms & Conditions</a></li>
                            <li><a href="{{ url('/') .'#aboutUsSection' }}">About</a></li>
                            <li><a href="{{ route('contactUs') }}">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="social_icon">
                    <ul>
                        <li>
                            <a href="">
                                <img src="{{ asset('img/footer/twitter.svg') }}" alt="">
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <img src="{{ asset('img/footer/facebook.svg') }}" alt="">
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <img src="{{ asset('img/footer/dribbble.svg') }}" alt="">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="copyright">
                    © Copyright 2018 - Ethiopay.com
                </div>
            </div>
        </div>
    </div>
</footer>