<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CommentsController extends Controller
{
    //
    public function postComment(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required',
        ]);

        $comment = Comment::create([
            'ticket_id' => $request->input('ticket_id'),
            'user_id' => auth()->guard('web')->user()->id,
            'comment' => $request->input('comment'),
        ]);
        // $request->session()->flash('success', "Your comment has be submitted.");
        $data = ['status' => true, 'message' => 'Comment Post Successfully', 'data' => $comment];
        return Response::json($data);
        // return redirect()->back()->with("status", "Your comment has be submitted.");
    }

    public function getComment($ticket_id, Request $req)
    {
        $ticket = Ticket::select('comments.id', 'comments.admin_id', 'tickets.ticket_id', 'comments.user_id', 'tickets.title', 'tickets.message', 'tickets.title',
            'comments.created_at',
            DB::raw('CONVERT_TZ(TIMESTAMP(comments.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS createdDate'),          
             'users.firstname', 'users.lastname', 'admins.name', 'comments.comment')
            ->leftJoin('comments', 'comments.ticket_id', 'tickets.id')
            ->leftjoin('users', 'comments.user_id', 'users.id')
            ->leftjoin('admins', 'comments.admin_id', 'admins.id')
            ->where('tickets.ticket_id', $ticket_id);

        if (($last_id = $req->get('last_id')) != '') {
            $ticket = $ticket->where('comments.id', '>', $last_id);
        }

        $ticket = $ticket->get();
        $data = ['status' => true, 'message' => 'Comment get Successfully', 'data' => $ticket];
        return Response::json($data);
    }
}
