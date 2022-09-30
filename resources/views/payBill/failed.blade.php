@extends('layouts.app')
@section("title", "PayBill Failed")

@section('content')
<main>
    <section>
        <div class="container">
            <div class="ep_thanku">
                <div class="thanku_header">
                    <h2 class="pay-fail-title">
                        Uh-Oh! We were unable to precess your payment.
                    </h2>
                </div>
                <div class="center_thanku">
                    <div id="printBillReceiptSection">
                        <div class="submit">
                            <div class="id">
                                Sorry, but we are having trouble processing your payment.You have not been charged for this transaction.
                            </div>
                            <div class="id">
                                Please try again.
                            </div>
                        </div>
                        <div class="refresh failblk">
                            <h2>
                                There was an error processing your payment please contact support
                            </h2>
                        </div>
                    </div>
                    <div id="btnSection" class="done_btn clearfix">
                        <a href="{{ url('home') }}" class="btn btn-default border">RETRY</a>
                        <a href="{{ url('home') }}" class=" btn btn-default">START OVER</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push("scripts")

<script type="text/javascript">

</script>
@endpush


