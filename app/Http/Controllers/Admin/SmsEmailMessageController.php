<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\library\TwilioLibrary;
use App\Models\SmsEmailMessage;
use App\Jobs\SmsEmailMessageJob;
use Yajra\Datatables\Datatables;
use App\Mail\UserEmailMessageMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Admin\Requests\SmsEmailMessageRequest;

class SmsEmailMessageController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        // $emailMessage = SmsEmailMessage::find(12);
        // $users_id = explode(",", $emailMessage->users_id);

        // foreach($users_id as $user_id) {
        //     $user = User::find($user_id);

        //     $emailQueueMessage = (new UserEmailMessageMail($user, $emailMessage))->onQueue('emails');
        //     Mail::to($user->email, $user->fullname)->queue($emailQueueMessage);
        // }

        // return new \App\Mail\UserEmailMessageMail($user, $emailMessage);

        return view("admin.sms-email-message.index");
    }

    public function getDatatable(Request $request) {
        // $smsEmailMessage = SmsEmailMessage::select([
        //     'sms_email_messages.id',
        //     'sms_email_messages.message_type',
        //     'sms_email_messages.sent_type',
        //     'sms_email_messages.sent_datetime',
        //     DB::raw('IF(sms_email_messages.sent_type = "now", "Sent", "Scheduled") AS sentStatus'),            
        //     DB::raw('CONVERT_TZ(TIMESTAMP(sms_email_messages.sent_datetime), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS sentDate'),            
        //     'sms_email_messages.title',
        //     DB::raw('GROUP_CONCAT(DISTINCT(CONCAT(users.firstname, " ", users.lastname, " (" , users.email, ")<br/>"))) as payoursName'),
        //     DB::raw('GROUP_CONCAT(DISTINCT(CONCAT(transactions.debtor_firstname, " ", transactions.debtor_lastname, " (", transactions.debtor_phone_code, transactions.debtor_phone_number, ")<br/>" ))) as debtorsName')
        //     ])
        // ->leftJoin('users', function ($join) {
        //     $join->on(DB::raw("find_in_set(users.id, sms_email_messages.users_id)"), ">", DB::raw("'0'"));
        // })
        // ->leftJoin('transactions', function ($join) {
        //     $join->on(DB::raw("find_in_set(CONCAT(transactions.debtor_phone_code,transactions.debtor_phone_number), sms_email_messages.debtors_phone)"), ">", DB::raw("'0'"))
        //     ->groupBy(DB::raw('CONCAT(transactions.debtor_phone_code,transactions.debtor_phone_number)'));
        // })
        // ->groupBy('sms_email_messages.id');

        // if ($keyword = $request->get('search')['value']) {
        //     $smsEmailMessage->having("id", 'like', "%$keyword%");
        //     $smsEmailMessage->orHaving("message_type", 'like', "%$keyword%");
        //     $smsEmailMessage->orHaving("sentStatus", 'like', "%$keyword%");
        //     $smsEmailMessage->orHaving('sentDate', 'LIKE', "%$keyword%");
        //     $smsEmailMessage->orHaving('title', 'LIKE', "%$keyword%");
        //     $smsEmailMessage->orHaving('payoursName', 'LIKE', "%$keyword%");
        //     $smsEmailMessage->orHaving('debtorsName', 'LIKE', "%$keyword%");
        // }

        // $datatables = Datatables::of($smsEmailMessage);
        // $datatables->filterColumn('id', function ($query, $keyword) {
            
        // })->filterColumn('message_type', function ($query, $keyword) {
            
        // })->filterColumn('sentStatus', function ($query, $keyword) {
            
        // })->filterColumn('sentDate', function ($query, $keyword) {
            
        // })->filterColumn('title', function ($query, $keyword) {
            
        // })->filterColumn('payoursName', function ($query, $keyword) {
            
        // })->filterColumn('debtorsName', function ($query, $keyword) {
            
        // });

        // return $datatables->make(true);

        $smsEmailMessage = SmsEmailMessage::select([
            'sms_email_messages.id',
            'sms_email_messages.sent_status','sms_email_messages.sent_type',            
            DB::raw('UPPER(sms_email_messages.message_type) as messageType'),
            DB::raw('IF(sms_email_messages.sent_status = "1", "Sent", "Schedule") AS sentStatus'),
            DB::raw('CONVERT_TZ(TIMESTAMP(sms_email_messages.sent_datetime), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS sentDate'),
            'sms_email_messages.title',
        ]);

        $datatables = Datatables::of($smsEmailMessage);
        $datatables->filterColumn('sentStatus', function ($query, $keyword) {
            $sql = 'IF(sms_email_messages.sent_status = "1", "Sent", "Schedule")  like ?';
            $query->whereRaw($sql, ["%{$keyword}%"]);
        })->filterColumn('sentDate', function ($query, $keyword) {
            $sql = 'CONVERT_TZ(TIMESTAMP(sms_email_messages.sent_datetime), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '")  like ?';
            $query->whereRaw($sql, ["%{$keyword}%"]);
        });

        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $users = User::get();
        $debtorsPhone = Transaction::select([
                'debtor_firstname', 'debtor_lastname',
                DB::raw('CONCAT(debtor_phone_code,debtor_phone_number) as debtorPhoneNumber')])
            ->groupBy(DB::raw('CONCAT(debtor_phone_code,debtor_phone_number)'))
            ->having('debtorPhoneNumber', '!=', "")
            ->get();
        // return $debtorsPhone;
        $viewData = [
            'users' => $users,
            'debtorsPhone' => $debtorsPhone,
        ];
        return view('admin.sms-email-message.create', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SmsEmailMessageRequest $request) {
        
        $input = $request->except('_token', 'submitBtn');

        $users_id = "";
        $debtors_phone = "";

        if($request->has('debtors_phone')) {
            $debtors_phone = implode(",", $input['debtors_phone']);
        }
        if($request->has('users_id')) {
            $users_id = implode(",", $input['users_id']);
        }

        if($input['users_selection_type'] == 'all') {
            if($input['message_type'] == 'email') {
            $users = User::select(DB::raw('group_concat(id) as users_id'))->first();
            $users_id = $users->users_id;
            $debtors_phone = "";
            } else {
                $users = User::select(DB::raw('group_concat(id) as users_id'))->first();
                $users_id = $users->users_id;

                $debtors = Transaction::select([
                    DB::raw('CONCAT(debtor_phone_code,debtor_phone_number) as debtorPhoneNumber')])
                ->groupBy(DB::raw('CONCAT(debtor_phone_code,debtor_phone_number)'))
                ->having('debtorPhoneNumber', '!=', "")
                ->get();

                $debtors_phone_array = [];
                foreach($debtors as $debtor) {
                    $debtors_phone_array[] = $debtor->debtorPhoneNumber;
                }
                $debtors_phone = implode(",",$debtors_phone_array);
            }
        }

        $smsEmailMessage = new SmsEmailMessage();
        $smsEmailMessage->message_type = $input['message_type'];
        $smsEmailMessage->sent_type = $input['sent_type'];
        // $smsEmailMessage->sent_status = '1';
        // $smsEmailMessage->sent_datetime = Carbon::now();
        $smsEmailMessage->users_selection_type = $input['users_selection_type'];
        $smsEmailMessage->users_id = $users_id;
        $smsEmailMessage->debtors_phone = $debtors_phone;
        $smsEmailMessage->title = $input['title'];
        $smsEmailMessage->description = $input['description'];

        if ($smsEmailMessage->sent_type == 'now') {
            $emailsentdatetime = Carbon::now('UTC')->toDateTimeString();
            $smsEmailMessage->sent_datetime = $emailsentdatetime;
            $smsEmailMessage->sent_status = '1';

        } elseif ($smsEmailMessage->sent_type == 'schedule') {
            $emailsmssentdatetime = Carbon::createFromFormat('Y-m-d H:i:s', $input['sentdatetime'], config('ethiopay.TIMEZONE_STR'))->setTimezone('UTC')->format('Y-m-d H:i:s');
            $smsEmailMessage->sent_datetime = $emailsmssentdatetime;
            $smsEmailMessage->sent_status = '0';
        }

        $smsEmailMessage->save();

        if ($smsEmailMessage->sent_type == 'now' && $smsEmailMessage->sent_status == '1') {
            SmsEmailMessageJob::dispatch($smsEmailMessage)->onQueue('smsEmailMessage');
        }

        Session::flash('success', 'A new message has been added successfully.');

        $data = array('status' => true, 'message' => 'New message added successfully.');
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
        $smsEmailMessage = SmsEmailMessage::select([
                'sms_email_messages.id', 'sms_email_messages.message_type',
                'sms_email_messages.sent_status','sms_email_messages.sent_type',
                'sms_email_messages.title', 'sms_email_messages.description',
                DB::raw('IF(sms_email_messages.sent_status = "1", "Sent", "Schedule") AS sentStatus'),
                DB::raw('CONVERT_TZ(TIMESTAMP(sms_email_messages.sent_datetime), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS sentDate'),
                DB::raw('GROUP_CONCAT(DISTINCT(CONCAT(" ", users.firstname, " ", users.lastname, " (" , users.email, ")"))) as payoursName'),
                DB::raw('GROUP_CONCAT(DISTINCT(CONCAT(" ",transactions.debtor_firstname, " ", transactions.debtor_lastname, " (", transactions.debtor_phone_code, transactions.debtor_phone_number, ")" ))) as debtorsName')
                ])
            ->leftJoin('users', function ($join) {
                $join->on(DB::raw("find_in_set(users.id, sms_email_messages.users_id)"), ">", DB::raw("'0'"));
            })
            ->leftJoin('transactions', function ($join) {
                $join->on(DB::raw("find_in_set(CONCAT(transactions.debtor_phone_code,transactions.debtor_phone_number), sms_email_messages.debtors_phone)"), ">", DB::raw("'0'"))
                ->groupBy(DB::raw('CONCAT(transactions.debtor_phone_code,transactions.debtor_phone_number)'));
            })
            ->where('sms_email_messages.id', $id)
            ->first();
        // return $smsEmailMessage;

        return view('admin.sms-email-message.show', compact('smsEmailMessage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
        $users = User::get();
        $debtorsPhone = Transaction::select([
                'debtor_firstname', 'debtor_lastname',
                DB::raw('CONCAT(debtor_phone_code,debtor_phone_number) as debtorPhoneNumber')])
            ->groupBy(DB::raw('CONCAT(debtor_phone_code,debtor_phone_number)'))
            ->having('debtorPhoneNumber', '!=', "")
            ->get();
        $smsEmailMessage = SmsEmailMessage::find($id);
        // return $debtorsPhone;
        $usersIdArray = [];
        $debtorsPhoneArray = [];
        if($smsEmailMessage->users_id) {
            $usersIdArray = explode(",", $smsEmailMessage->users_id);
        }
        if($smsEmailMessage->debtors_phone) {
            $debtorsPhoneArray = explode(",", $smsEmailMessage->debtors_phone);
        }

        $viewData = [
            'users' => $users,
            'debtorsPhone' => $debtorsPhone,
            'smsEmailMessage' => $smsEmailMessage,
            'usersIdArray' => $usersIdArray,
            'debtorsPhoneArray' => $debtorsPhoneArray
        ];
        return view('admin.sms-email-message.edit', $viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SmsEmailMessageRequest $request, $id) {
        //
        $input = $request->except('_token', 'submitBtn');

        $users_id = "";
        $debtors_phone = "";

        if($request->has('debtors_phone')) {
            $debtors_phone = implode(",", $input['debtors_phone']);
        }
        if($request->has('users_id')) {
            $users_id = implode(",", $input['users_id']);
        }

        if($input['users_selection_type'] == 'all') {
            if($input['message_type'] == 'email') {
            $users = User::select(DB::raw('group_concat(id) as users_id'))->first();
            $users_id = $users->users_id;
            $debtors_phone = "";
            } else {
                $users = User::select(DB::raw('group_concat(id) as users_id'))->first();
                $users_id = $users->users_id;

                $debtors = Transaction::select([
                    DB::raw('CONCAT(debtor_phone_code,debtor_phone_number) as debtorPhoneNumber')])
                ->groupBy(DB::raw('CONCAT(debtor_phone_code,debtor_phone_number)'))
                ->having('debtorPhoneNumber', '!=', "")
                ->get();

                $debtors_phone_array = [];
                foreach($debtors as $debtor) {
                    $debtors_phone_array[] = $debtor->debtorPhoneNumber;
                }
                $debtors_phone = implode(",",$debtors_phone_array);
            }
        }

        $smsEmailMessage = SmsEmailMessage::find($id);
        $smsEmailMessage->message_type = $input['message_type'];
        $smsEmailMessage->sent_type = $input['sent_type'];
        // $smsEmailMessage->sent_status = '1';
        // $smsEmailMessage->sent_datetime = Carbon::now();
        $smsEmailMessage->users_selection_type = $input['users_selection_type'];
        $smsEmailMessage->users_id = $users_id;
        $smsEmailMessage->debtors_phone = $debtors_phone;
        $smsEmailMessage->title = $input['title'];
        $smsEmailMessage->description = $input['description'];
        if ($smsEmailMessage->sent_type == 'now') {
            $emailsentdatetime = Carbon::now('UTC')->toDateTimeString();
            $smsEmailMessage->sent_datetime = $emailsentdatetime;
            $smsEmailMessage->sent_status = '1';

        } elseif ($smsEmailMessage->sent_type == 'schedule') {
            $emailsmssentdatetime = Carbon::createFromFormat('Y-m-d H:i:s', $input['sentdatetime'], config('ethiopay.TIMEZONE_STR'))->setTimezone('UTC')->format('Y-m-d H:i:s');
            $smsEmailMessage->sent_datetime = $emailsmssentdatetime;
            $smsEmailMessage->sent_status = '0';
        }
        $smsEmailMessage->save();

        if ($smsEmailMessage->sent_type == 'now' && $smsEmailMessage->sent_status == '1') {
            SmsEmailMessageJob::dispatch($smsEmailMessage)->onQueue('smsEmailMessage');
        }

        Session::flash('success', 'A message has been updated successfully.');

        $data = array('status' => true, 'message' => 'message updated successfully.');
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request) {
        return SmsEmailMessage::destroy($id);
    }

}
