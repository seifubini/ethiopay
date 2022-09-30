@extends('layouts.beforeLogin1')
@section("title", "Contact Us")

@section('content')
<section class="moreabt_section">
    <div class="container">
        <div class="inner_moreabtdtl">
            <div class="aboutrow_section">
                <div id="firstSection" class="aboutrow clearfix">
                    <div class="abt_lefttitle">
                        <h2>Send payments back home in an easy and secure way.</h2>
                    </div>    
                    <div class="abt_desc">
                        <p>Taking care of your loved ones back home does not need to be complicated. EthioPay makes it easy to send money without having to worry whether the money will reach your loved ones. Our secure platform uses bank-grade encryption meaning when you send money, your loved ones are guaranteed to receive it. You also never have to worry about your payment methods being compromised.</p>
                    </div>    
                </div>
                <div id="secondSection" class="aboutrow clearfix">
                    <div class="abt_lefttitle">
                        <h2>Use our one-time payment system or set an ongoing schedule.</h2>
                    </div>    
                    <div class="abt_desc">
                        <p>We know that sending money home depends on multiple factors. Sometimes you will want to send a one-time amount, other times you will want recurrent payments. Ethiopay caters for both instances by offering you flexible payment options that you can adjust to your needs. This helps you ensure that you never miss a payment when it's needed.</p>
                    </div>    
                </div>
                <div id="thirdSection" class="aboutrow clearfix">
                    <div class="abt_lefttitle">
                        <h2>Avoid the headaches of keeping track of payments.</h2>
                    </div>    
                    <div class="abt_desc">
                        <p>You want to keep track of payments you make for your personal records. This can be a challenge when using other platforms. The Ethiopay User Account Dashboard offers a unified and detailed view of all the transactions you have made on the platform. Each entry shows the date and time stamp, amount, recipient and transaction status of every payment made. </p>
                    </div>    
                </div>
                <div id="forthSection" class="aboutrow clearfix">
                    <div class="abt_lefttitle">
                        <h2>Pay education &amp; utility bills back home with Ethiopay</h2>
                    </div>    
                    <div class="abt_desc">
                        <p>Paying utility and school fees is an important way of empowering your loved ones back home. Whether you are in North America or Europe, Ethiopay offers an easy and reliable way to do this. In addition, you can opt to set up scheduled payments so the payments are made at the beginning of each school term or any other time frame your choose. </p>
                    </div>    
                </div>
            </div>
            <div class="clearfix">
                <a href="{{ url('register') }}"><button class="btn btn-default" type="button">GET STARTED</button></a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script type="text/javascript">
    if (window.location.hash) {
        scroll(0, 0);
    }

    setTimeout(function () {
        scroll(0, 0);
    }, 1);
    
    $(document).on('click', 'header .navbar-nav .aboutUs', function (event) {
        $(this).attr('href', '#thirdSection')
        event.preventDefault();
        $('html, body').animate({
            scrollTop: $($.attr(this, 'href')).offset().top + 'px'
        }, 1000, 'swing');
    });
    if (window.location.hash) {
        $('html,body').animate({
            scrollTop: $(window.location.hash).offset().top + 'px'
        }, 1000, 'swing');
    }
</script>
@endpush