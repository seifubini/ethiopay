@extends('layouts.beforeLogin1')
@section("title", "Welcome")

@section('content')
<main class="padding-none">
    <section class="ep_banner">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="slider_list"> <img src={{asset('img/banner.png')}} alt="">
                        <div class="container">
                            <div class="slider_data clearfix">
                                <h2>Remit to Pay</h2>
                                <p>EthioPay offers a convenient and safe way to pay your loved ones’ bills from anywhere in the world.</p>
                                <a href="{{ url('register') }}" class="btn btn-default">Get Started</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="slider_list"> <img src={{asset('img/banner_slider2.jpg')}} alt="">
                        <div class="container">
                            <div class="slider_data clearfix">
                                <h2>Bill Reminders</h2>
                                <p>EthioPay sends convenient bill reminders so you never miss a bill payment again.</p>
                                <a href="{{ url('register') }}" class="btn btn-default">Get Started</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="slider_list"> <img src={{asset('img/banner_slider3.jpg')}} alt="">
                        <div class="container">
                            <div class="slider_data clearfix">
                                <h2>Secure Payments</h2>
                                <p>EthioPay uses bank-grade security so you know your funds and transactions are always secure. </p>
                                <a href="{{ url('register') }}" class="btn btn-default">Get Started</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add Arrows -->
            <div class="container swiper-btn">
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
        <div class="banner_content container">
            <div class="row">
                <div class="col-md-12">
                    <div class="content_data">
                        <div class="content_list">
                            <h3>
                                Send payments back home in an easy and secure way
                            </h3>
                            <p>
                                Taking care of your loved ones back home does not need to be complicated. EthioPay makes it
                                easy to send money without having to worry whether the money will reach your loved ones. Our
                                secure platform uses bank-grade encryption meaning when you send money, your loved ones
                                are guaranteed to receive it. You also never have to worry about your payment methods being
                                compromised.
                            </p>
                            <a target="_blank" href="{{ url('more-about-us') .'#firstSection' }}"> READ MORE …</a>
                        </div>
                        <div class="content_list">
                            <h3>
                                Use our one-time payment system or set an ongoing schedule
                            </h3>
                            <p>
                                We know that sending money home depends on multiple factors. Sometimes you will want to
                                send a one-time amount, other times you will want recurrent payments. Ethiopay caters for both
                                instances by offering you flexible payment options that you can adjust to your needs. This helps
                                you ensure that you never miss a payment when it's needed.
                            </p>
                            <a target="_blank" href="{{ url('more-about-us') .'#secondSection' }}"> READ MORE …</a>
                        </div>
                        <div class="content_list">
                            <h3>
                                Get notified about every transaction made with us 
                            </h3>
                            <p>
                                To build even more security into our platform, we make it easy for you to know whenever a
                                transaction has been run on your account. This means that if you’ve set scheduled payments,
                                you will be notified every time the payment runs. This also makes it convenient as you do not
                                have to keep checking your account to see whether the payment was successful.
                            </p>
                            <a target="_blank" href="{{ url('more-about-us') .'#thirdSection' }}"> READ MORE …</a>
                        </div>
                        <div class="content_list">
                            <h3>
                                Avoid the headaches of keeping track of payments
                            </h3>
                            <p>
                                You want to keep track of payments you make for your personal records. This can be a
                                challenge when using other platforms. The Ethiopay User Account Dashboard offers a unified
                                and detailed view of all the transactions you have made on the platform. Each entry shows the
                                date and time stamp, amount, recipient and transaction status of every payment made.
                            </p>
                            <a target="_blank" href="{{ url('more-about-us') .'#forthSection' }}"> READ MORE …</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="ep_main_section">
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="icon_btn">
                            <a href="{{ url("pay-bill/service-type/".env('PAYBILL_SERVICE_TYPE_UTILITY_BILLS_ID')) }}" class="icon_list"> 
                                <img src="img/bill.svg" alt="">
                                <h4>UTILITY BILLS</h4> 
                            </a>
                            <a href="{{ url("pay-bill/service-type/".env('PAYBILL_SERVICE_TYPE_HEALTH_INSURANCE_ID')) }}" class="icon_list"> 
                                <img src="img/insurance.svg" alt="">
                                <h4>HEALTH INSURANCE</h4>  
                            </a>
                            <a href="{{ url("pay-bill/service-type/".env('PAYBILL_SERVICE_TYPE_SCHOOL_FEES_ID')) }}" class="icon_list"> 
                                <img src="img/fees.svg" alt="">
                                <h4>SCHOOL FEES</h4>  
                            </a>
                            <a href="{{ url("pay-bill/service-type/".env('PAYBILL_SERVICE_TYPE_PAY_EDIR_ID')) }}" class="icon_list"> 
                                <img src="img/telecom.svg" alt="">
                                <h4>PAY TELECOM</h4>  
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="ep_autopay">
            <div class="container">
                <div class="row">
                    <div id="whyChooseEthiopaySection" class="col-md-6">
                        <div class="autopay_left">
                            <div class="autopay_data auto_text">
                                <button type="button" class="btn btn-default">ABOUT US</button>
                                <h2>
                                    Why choose Ethiopay?
                                </h2>
                                <p>
                                    Ethiopay operates a flexible, tightly regulated, global payout platform. This enables you to automate your user payments flow, pay less for your transactions, access flexible online reports, guarantee the integrity of all your payment traffic, ensure your payments go through as planned….the list goes on.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="autopay_right">
                            <div class="autopay_learn clearfix">
                                <div class="learn_img"> </div>
                                <div class="learn_data">
                                    <h2>Get Started!</h2>
                                    <p>Do you have familiy in Ethiopia? Do you want to get their bills covered? We've the solution to guarantee the payment of the bills. Create your account, request for the bill in our system and pay it. As simple as that!</p>
                                    {{-- <p>This allows you to make online payments without creating an Ethiopay account. You will only need to enter the service information and click Verify so that we may confirm your account information before proceeding to the payment page.</p> --}}
                                    <!--<button type="button" class="btn btn-default"> Learn More </button>-->
                                </div> 
                            </div>
                            <div class=" autopay_info">
                                <div class="info_data auto_text">
                                    <button type="button" class="btn btn-default">INFORMATION</button>
                                    <h2>
                                        How does Ethiopay works?
                                    </h2>
                                    <p>Video Coming Soon</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="ep_article">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
<!--                        <div id="missionStatementSection" class="article_left clearfix">
                            <div class="article_img">
                                <div class="article_pic"> <img src="img/article1.png" alt=""> </div>
                                <button type="button" class="btn btn-default">Ethiopay Mission</button>
                            </div>
                            <div class="article_data">
                                <h3>BIO/Mission Statement</h3>
                                <p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce faucibus vehicula erat rhoncus sagittis. Maecenas posuere libero urna, at malesuada justo consectetur eu. Vivamus quis tempor orci, interdum tincidunt lectus. Nulla quis mauris ligula. Nulla facilisi. Sed nec risus sit amet enim faucibus finibus sit amet non urna. Donec tincidunt volutpat ex vel mollis. Curabitur non erat malesuada, egestas ipsum in, condimentum nulla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nullam eu iaculis enim. Nullam ornare pulvinar velit, non aliquam ligula tincidunt eget. Proin facilisis ipsum at ligula egestas, eget posuere dolor rutrum. Mauris eget nulla ullamcorper, mattis neque at, fermentum elit. Donec sagittis, est eu tincidunt dapibus, diam nulla commodo massa, vel vestibulum nisl diam id enim.</p>
                                <a href="javascript:void(0)">READ MORE</a>
                            </div>
                        </div>-->
                        <div class="article_left clearfix">
                            <div class="article_img">
                                <div class="article_pic"> <img src="img/article2.png" alt=""> </div>
                                <button type="button" class="btn btn-default">faq</button>
                            </div>
                            <div class="article_data">
                                <h3>Pay education & utility bills back home with Ethiopay</h3>
                                <p>
                                    Paying utility and school fees is an important way of empowering your loved ones back home.
                                    Whether you are in North America or Europe, Ethiopay offers an easy and reliable way to do
                                    this. In addition, you can opt to set up scheduled payments so the payments are made at the
                                    beginning of each school term or any other time frame your choose.
                                </p>
                                <!--<a href="javascript:void(0)">READ MORE</a>-->
                            </div>
                        </div>
                        <div id="aboutUsSection" class="article_left clearfix">
                            <div class="article_img">
                                <div class="article_pic"> <img src="img/article1.png" alt=""> </div>
                                <button type="button" class="btn btn-default">About us</button>
                            </div>
                            <div class="article_data">
                                <h3>About EthioPay</h3>
                                <p>
                                    As Ethiopians ourselves, we set out to build Ethiopay as a platform that meets the payment
                                    needs of Ethiopians in the diaspora. While other platforms are a one-size-fits-all, Ethiopay isn’t.
                                    We support the unique payment needs of Ethiopians including payments for school fees,
                                    telecom, utility bills and other needs that are close to your heart. Our vision is a future where
                                    Ethiopians in the the diaspora touch the lives of those back home through easy, secure and
                                    seamless payments. To achieve this, we are on a mission to build the most reliable payments
                                    platform serving Ethiopians in the world. We want to make it so easy for you to send money
                                    home that your loved ones will feel as though you were right there with them.
                                </p>
                                <!--<a href="javascript:void(0)">READ MORE</a>-->
                            </div>
                        </div>
                        <br/>
                    </div>
<!--                    <div class="col-md-3">
                        <div class="article_right">
                            <ul>
                                <li> <a href="#aboutUsSection">ETHIOPAY</a> </li>
                                <li> <a href="javascript:void(0)">OUR PRODUCTS</a> </li>
                                <li> <a href="#whyChooseEthiopaySection">WHY ETHIOPAY</a> </li>
                                <li> <a href="#missionStatementSection">ABOUT</a> </li>
                                <li> <a href="javascript:void(0)">CONTACT</a> </li>
                                <li> <a href="{{ route('register') }}">SIGN UP</a> </li>
                            </ul>
                        </div>
                        <div class="article_right bottom_space">
                            <div class="latest_article"> LATEST ARTICLES </div>
                            <div class="latest_data">
                                <div class="latest_list"> Send payments back home in an easy an secure way </div>
                                <div class="latest_list"> Use our one-time payment system or set an ongoing schedule </div>
                                <div class="latest_list"> Send payments back home in an easy an secure way </div>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

@push("scripts")
<script type="text/javascript">
    var swiper = new Swiper('.swiper-container', {
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
    });
</script>
@endpush