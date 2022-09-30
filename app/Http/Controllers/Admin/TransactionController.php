<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Response;
use Yajra\Datatables\Datatables;

class TransactionController extends Controller
{
    //
    public function __construct()
    {
        $this->moduleName = "Transaction";
        $this->moduleRoute = url('admin/transaction');
        $this->moduleView = "admin.transaction";

        View::share('module_name', $this->moduleName);
        View::share('module_route', $this->moduleRoute);
        View::share('moduleView', $this->moduleView);

    }

    public function index()
    {
        return view($this->moduleView . '.index');
    }

    public function getDatatable(Request $request)
    {
        $transactions = Transaction::
            select('transactions.*', 'users.firstname', 'users.lastname', 'service_providers.provider_name',
            DB::raw('CONCAT(users.firstname," ",users.lastname) as fullname'),
            DB::raw('CONCAT(transactions.debtor_firstname," ",transactions.debtor_lastname) as debtorFullname'),            
            DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%d / %M") AS date'),
            DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%d / %M / %Y") AS fromToDate'),
            DB::raw('IF(transactions.transaction_status = "failed", "FAIL", "COMPLETE") as status'),                        
            DB::raw('CONCAT("$", FORMAT(transactions.total_pay_amount, 2)) as amount'))
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->join('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id');

        if(($startingDate = $request->get('startingDate')) != ''){
            $transactions->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), '>=', $startingDate);
        }

        if(($endDate = $request->get('endDate')) != ''){
            $transactions->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), '<=', $endDate);
        }
    
        // if (($from_date = trim($request->get('from_date'))) != '') {
        //     $from_date_format = Carbon::createFromFormat('d / M / Y', $from_date)->format('Y-m-d');
        //     $transactions->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), '>=', $from_date_format);
        // }

        // if (($to_date = $request->get('to_date')) != '') {
        //     $to_date_format = Carbon::createFromFormat('d / M / Y', $to_date)->format('Y-m-d');
        //     $transactions->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(transactions.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), '<=', $to_date_format);
        // }

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //        return view($this->moduleView . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::select("transactions.*", 'service_providers.provider_name',
            'service_types.service_name',
            DB::raw('CONCAT(users.firstname," ",users.lastname) as fullname'))
            ->join('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
            ->join('service_types', 'service_providers.service_type_id', '=', 'service_types.id')
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->where('transactions.id', $id)
            ->first();

        $payment = PaymentMethod::where('id', $transaction->payment_method_id)->first();

        return view($this->moduleView . '.show', compact('transaction', 'payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, SettingRequest $request)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
    }

}
