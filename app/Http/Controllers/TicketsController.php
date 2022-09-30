<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class TicketsController extends Controller
{
    //

    public function index()
    {
        return view('tickets.index');
    }

    public function getDatatable(Request $request)
    {
        $tickets = Ticket::select('tickets.id', 'tickets.ticket_id',
            'tickets.user_id', 'tickets.transaction_id', 'tickets.title', 'tickets.status',
            'service_types.service_name',
            DB::raw('CONCAT(users.firstname," ",users.lastname) as fullname'),
            DB::raw('CONCAT(transactions.debtor_firstname," ",transactions.debtor_lastname) as debtorFullname'),                        
            DB::raw('CONCAT("$",FORMAT(transactions.total_pay_amount,2)) as amount'),
            DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(tickets.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%d / %b") AS date'))
            ->join('transactions', 'transactions.id', '=', 'tickets.transaction_id')
            ->join('service_providers', 'service_providers.id', '=', 'transactions.service_provider_id')
            ->join('service_types', 'service_types.id', '=', 'service_providers.service_type_id')
            ->join('users', 'users.id', '=', 'tickets.user_id')
            ->where('tickets.user_id', auth()->guard('web')->user()->id);
        if (($from_date = trim($request->get('from_date'))) != '') {
            $from_date_format = Carbon::createFromFormat('d / M / Y', $from_date)->format('Y-m-d');
            $tickets->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(tickets.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), '>=', $from_date_format);
        }

        if (($to_date = trim($request->get('to_date'))) != '') {
            $to_date_format = Carbon::createFromFormat('d / M / Y', $to_date)->format('Y-m-d');
            $tickets->where(DB::raw('DATE_FORMAT(CONVERT_TZ(TIMESTAMP(tickets.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d")'), '<=', $to_date_format);
        }
        return Datatables::of($tickets)->make(true);
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'message' => 'required',
            'transaction_id' => 'required',
        ]);

        $ticket = new Ticket([
            'title' => $request->input('title'),
            'user_id' => auth()->guard('web')->user()->id,
            'transaction_id' => $request->input('transaction_id'),
            'ticket_id' => $this->uniqueTicketId(),
            'message' => $request->input('message'),
            'status' => "Open",
        ]);

        $ticket->save();

        return redirect('/tickets')->with("status", "A ticket with ID: #$ticket->ticket_id has been opened.");
    }

    public function uniqueTicketId()
    {
        $randomString = strtoupper(str_random(10));

        $unique_id = Ticket::where('ticket_id', $randomString)->first();
        if($unique_id){
            $this->uniqueTicketId();
        }
        return $randomString;
    }

    public function show($id)
    {
        $ticket = Ticket::find($id);
        $comments = $ticket->comments;
        return view('tickets.show', compact('ticket', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validateTransactionId(Request $req)
    {
        $user_id = auth()->guard('web')->user()->id;
        $transaction_id = $req->get('transaction_id');
        $checkTransactionId = array();

        $transaction = Transaction::where('random_transaction_id', $transaction_id)->first();

        if ($transaction) {
            if ($transaction->user_id != $user_id) {
                $checkTransactionId['status'] = 'false';
                $checkTransactionId['message'] = 'Enter valid transaction id.';
            } else {
                $checkTransactionId['status'] = 'true';
                $checkTransactionId['message'] = '';
            }
        } else {
            $checkTransactionId['status'] = 'false';
            $checkTransactionId['message'] = 'Enter valid transaction id.';
        }
        return $checkTransactionId;
    }
}
