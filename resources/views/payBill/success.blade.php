@extends('layouts.app')
@section("title", "PayBill Success")

@section('content')
<main>
    <section>
        <div class="container">
            <div class="ep_thanku">
                <div class="thanku_header">
                    <h2>
                        Thank you! Your Payment has been submitted.
                    </h2>
                </div>
                <div class="center_thanku">
                    <div id="printBillReceiptSection">
                        <div class="submit">
                            <h2>
                                Submitted on {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at, 'UTC')->setTimezone(config('ethiopay.TIMEZONE_STR'))->format('M d, Y') }}
                            </h2>
                            <h1>
                                ${{ number_format($transaction->total_pay_amount, 2) }}
                            </h1>
                            <div class="id">
                                Transaction ID
                            </div>
                            <div class="id_no">
                                {{ $transaction->random_transaction_id }}
                            </div>
                            <div class="email">
                                Email addres for paperless billing alerts
                            </div>
                            <div class="email_address">
                                {{ auth()->guard('web')->user()->email }}
                            </div>
                        </div>
                        <div class="refresh">
                            <h2>
                                It may take up to 1 business day for us to process this payment
                            </h2>
                        </div>
                    </div>
                    <div id="btnSection" class="done_btn clearfix">
                        <button id="printBillReceiptBtn" type="button" class="btn btn-default border">Print this confirmation</button>
                        <a href="{{ url('home') }}" class=" btn btn-default">I’m Done</a>
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

$("#printBillReceiptBtn").on("click", function () {
//    $('#btnSection').addClass('hide');
    getElementCanvasForPdf('#printBillReceiptSection', function (canvas) {
        var pdfDoc = new jsPDF('p', 'pt', [canvas.width-100, 500]); //[600, 700]
        pdfDoc.addHTML($('#printBillReceiptSection').get(0), 0, 0, JS_PDF_CONFIG_OPTIONS, function () {
//            $('#btnSection').removeClass('hide');
            //pdfDoc.save("bill-receipt_"+"{{ $transaction->random_transaction_id }}"+".pdf");
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


