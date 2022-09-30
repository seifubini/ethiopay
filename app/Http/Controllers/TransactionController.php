<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class TransactionController extends Controller
{
    //
    public function index()
    {
        return view('transaction.index');
    }

    public function getDatatable(Request $request)
    {
        $user_id = auth()->guard('web')->user()->id;
        $transactions = Transaction::
            select('transactions.*', 'users.firstname', 'users.lastname', 'service_providers.provider_name',
            DB::raw('CONCAT(users.firstname," ",users.lastname) as fullname'),
            DB::raw('CONCAT(transactions.debtor_firstname," ",transactions.debtor_lastname) as debtorFullname'),            
            DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%d / %b") AS date'),
            DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%d / %M / %Y") AS fromToDate'),
            DB::raw('IF(transactions.transaction_status = "failed", "FAIL", "COMPLETE") as status'),
            DB::raw('CONCAT("$", FORMAT(transactions.total_pay_amount, 2)) as amount'))
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->join('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
            ->where('user_id', $user_id);

        if (($from_date = trim($request->get('from_date'))) != '') {
            $from_date_format = Carbon::createFromFormat('d / M / Y', $from_date)->format('Y-m-d');
            $transactions->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), '>=', $from_date_format);
        }

        if (($to_date = $request->get('to_date')) != '') {
            $to_date_format = Carbon::createFromFormat('d / M / Y', $to_date)->format('Y-m-d');
            $transactions->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), '<=', $to_date_format);
        }

        return Datatables::of($transactions)
            ->filterColumn('created_at', function ($query, $keyword) {
                $sql = 'DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%d / %M")  like ?';
                $query->whereRaw($sql, ["%{$keyword}%"]);
                // $query->having("date", 'like', "%$keyword%");
            })
            ->filterColumn('provider_name', function ($query, $keyword) {
                $query->whereRaw("service_providers.provider_name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('amount', function ($query, $keyword) {
                $sql = 'CONCAT("$", FORMAT(transactions.total_pay_amount, 2)) like ?';
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('fullname', function ($query, $keyword) {
                $sql = "CONCAT(users.firstname,' ',users.lastname)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
                // $query->having("fullname like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('debtorFullname', function ($query, $keyword) {
                $sql = "CONCAT(transactions.debtor_firstname,' ',transactions.debtor_lastname) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->whereRaw("IF(transactions.transaction_status = 'failed', 'FAIL', 'COMPLETE') like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function show($id, Request $request)
    {
        $user_id = auth()->guard('web')->user()->id;        
        $transaction = Transaction::select("transactions.*", 'service_providers.provider_name',
            'service_types.service_name')
            ->join('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
            ->join('service_types', 'service_providers.service_type_id', '=', 'service_types.id')
            ->where('transactions.id', $id)
            ->where('transactions.user_id', $user_id)
            ->first();

        if ($transaction) {
            $payment = PaymentMethod::where('id', $transaction->payment_method_id)->first();

            return view('transaction.show', compact('transaction', 'payment'));
        }

        $request->session()->flash('errorAlert', "Transaction Not Found.");
        return redirect('home');
    }
}
