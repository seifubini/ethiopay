<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;

class TicketsController extends Controller
{
    //
    public function __construct() {
        $this->moduleName = "Ticket";
        $this->moduleRoute = url('admin/tickets');
        $this->moduleView = "admin.tickets";

        View::share('module_name', $this->moduleName);
        View::share('module_route', $this->moduleRoute);
        View::share('moduleView', $this->moduleView);

    }

    public function index()
    {
        return view($this->moduleView.'.index');
    }

    public function getDatatable()
    {
        $tickets = Ticket::select('tickets.id','tickets.ticket_id','tickets.transaction_id','tickets.title','tickets.status',
        DB::raw('CONCAT(users.firstname," ",users.lastname) as userFullname'),
        DB::raw('CONCAT(transactions.debtor_firstname," ",transactions.debtor_lastname) as debtorFullname')
        )
        ->join('transactions', function ($join) {
            $join->on('transactions.random_transaction_id', '=', 'tickets.transaction_id');
        })
        ->join('users', function ($join) {
            $join->on('users.id', '=', 'tickets.user_id');
        })
        ->groupBy('tickets.id');
        
        return Datatables::of($tickets)
            ->addColumn('lastUpdateDate', function ($tickets) {
                $comment = Comment::select(DB::raw('CONVERT_TZ(TIMESTAMP(comments.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS date'))
                ->orderBy('created_at', 'DESC')
                ->where('ticket_id', $tickets->id)
                ->whereNotNull('admin_id')
                ->first();
                if($comment)
                    return $comment->date;
                else
                    return " ";
            })

            ->addColumn('lastResponseAdminName', function ($tickets) {
                $comment = Comment::select('admins.name',DB::raw('CONVERT_TZ(TIMESTAMP(comments.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS date'))
                ->join('admins', 'admins.id', '=', 'comments.admin_id')
                ->orderBy('comments.created_at', 'DESC')
                ->where('comments.ticket_id', $tickets->id)
                ->whereNotNull('comments.admin_id')
                ->first();
                if($comment)
                    return $comment->name;
                else
                    return " ";
            })

            ->filterColumn('userFullname', function ($query, $keyword) {
                $sql = "CONCAT(users.firstname,' ',users.lastname)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('debtorFullname', function ($query, $keyword) {
                $sql = "CONCAT(transactions.debtor_firstname,' ',transactions.debtor_lastname) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            // ->filterColumn('lastUpdateDate', function ($query, $keyword) {
            //     $sql = 'DATE_FORMAT(CONVERT_TZ(TIMESTAMP(comments.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '"), "%Y-%m-%d %H:%i:%s") like ?';
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            //     // $query->having("date", 'like', "%$keyword%");
            // })
            ->make(true);
    }

    public function show($id)
    {
        $ticket = Ticket::find($id);
        $comments = $ticket->comments;
        $user = $ticket->user;
        return view($this->moduleView.'.show', compact('ticket', 'comments', 'user'));
    }

    public function changeStatus($ticket_id, Request $request)
    {
        $ticket = Ticket::find($ticket_id);

        $ticket->status =  $request->status;

        $ticket->save();
        addToLog('The ticket status change successfully.', 'tickets', json_encode($ticket));        
        
        $request->session()->flash('success', "The ticket status change successfully.");

        $data = ['status' => true, 'message' => "The ticket status has been change successfully successfully"];
        return Response::json($data);
    }
}
