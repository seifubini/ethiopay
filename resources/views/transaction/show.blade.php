@extends('layouts.app')

@section('title', 'View Transaction')

@section('content')
<main>
    <section class="transactions">
        <div class="container">
            <h3>View Transaction</h3>
            <div class="row">
                <div class="records">
                <div class="col-md-12">
                    <div id="printTransactionSection">
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Name</label>
                            <div>
                                {{ auth()->guard('web')->user()->fullname }}
                            </div>
                        </div>  
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Random Transaction Id</label>
                            <div>
                                {{ $transaction->random_transaction_id }}
                            </div>
                        </div>     
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Service Type</label>
                            <div>
                                {{ $transaction->service_name }}
                            </div>
                        </div>   
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Service provider</label>
                            <div>
                                {{ $transaction->provider_name }}
                            </div>
                        </div> 
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Debtor Name</label>
                            <div>
                                {{ $transaction->debtor_firstname }} {{$transaction->debtor_lastname}}
                            </div>
                        </div> 
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Debtor City</label>
                            <div>
                                {{ $transaction->debtor_city }}
                            </div>
                        </div> 
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Debtor Phone Number</label>
                            <div>
                                {{ $transaction->debtor_phone_code }}{{ $transaction->debtor_phone_number }}
                            </div>
                        </div> 
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Cut Off Date</label>
                            <div>
                                {{ $transaction->cut_off_date }}
                            </div>
                        </div> 

                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Payment Type</label>
                            <div>
                                {{ ucfirst($payment->method_type) }}
                                <img id="transactionCardImage" src="{{ $payment->payment_method_icon }}">
                            </div>
                        </div>   
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Card Number</label>
                            <div>
                                {{ $payment->card_number }}
                            </div>
                        </div>      
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Customer Service Number</label>
                            <div>
                                {{ $transaction->customer_service_number }}
                            </div>
                        </div>   
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Customer Pay Amount</label>
                            <div>
                                ${{ $transaction->customer_pay_amount }}
                            </div>
                        </div>                         
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Commision In Percentage</label>
                            <div>
                                {{ $transaction->commision_in_percentage }}%
                            </div>
                        </div>   
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Commision In Amount</label>
                            <div>
                                ${{ $transaction->commision_amount }}
                            </div>
                        </div>   
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Total Pay Amount</label>
                            <div>
                                ${{ $transaction->total_pay_amount }}
                            </div>
                        </div>   
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Date</label>
                            <div>
                                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at, 'UTC')->setTimezone(config('ethiopay.TIMEZONE_STR'))->format('Y-m-d H:i:s') }}                                
                            </div>
                        </div>     
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Status</label>
                            <div>
                                @if($transaction->transaction_status == 'failed')
                                    Fail
                                @else
                                    Complete
                                @endif
                            </div>
                        </div>
                    </div> 
                
                    <div class="form-group clearfix">
                        <a href="#" id="printTranscationDetail" class="btn btn-default">Print this Transaction</a>
                        <a href="{{ url("/transaction") }}" class="btn btn-warning">Back</a>                                                
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push("scripts")
<!-- Html to PDF generate -->
<script type="text/javascript" src="{{ asset('js/plugins/spdf/spdf.debug.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/html2canvas/html2canvas.js') }}"></script>

<script type="text/javascript">
var JS_PDF_CONFIG_OPTIONS = {
    pagesplit: true,
    background: '#fff',
    useCORS: true,
    logging: true,
    imageTimeout: 2000,
    letterRendering: true
};

$("#printTranscationDetail").on("click", function () {
//    $('#btnSection').addClass('hide');
    getElementCanvasForPdf('#printTransactionSection', function (canvas) {
        var pdfDoc = new jsPDF('p', 'pt', [canvas.width, 600]); //[600, 700]
        pdfDoc.addHTML($('#printTransactionSection').get(0), 0, 0, JS_PDF_CONFIG_OPTIONS, function () {
            pdfDoc.autoPrint();
            window.open(pdfDoc.output('bloburl'), '_blank');
        });
    });
});

function getElementCanvasForPdf(elementID, callback) {
    var targetElementDOM = $(elementID);
    html2canvas(targetElementDOM, {
        useCORS: true, // MUST
        logging: true,
        imageTimeout: 2000,
        background: '#FFFFFF',
        letterRendering: true,
        onrendered: function (canvas) {
            callback(canvas);
        },
        onclone: function (document) {

        }
    }).then(function (canvas) {

    });
}
</script>
@endpush