<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\ServiceType;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class ActivityLogController extends Controller
{
    public function index()
    {
        return view('admin.activity-log.index');
    }

    public function getDatatable()
    {
        $activityLog = ActivityLog::select('activity_logs.*', 'admins.name')
            ->join('admins', 'admins.id', '=', 'activity_logs.admin_id');
        return Datatables::of($activityLog)
            ->filterColumn('name', function ($query, $keyword) {
                $sql = "admins.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function show($id)
    {
        $activityLog = ActivityLog::find($id);
        $admin = Admin::where('id', $activityLog->admin_id)->first();

        $logInfo = (array) json_decode($activityLog->activity_description);
        unset($logInfo['created_at']);
        unset($logInfo['updated_at']);
        unset($logInfo['deleted_at']);
        if ($activityLog->type == 'tickets') {
            $user = User::select('firstname', 'lastname')->where('id', $logInfo['user_id'])->first();
            unset($logInfo['user_id']);
            $logInfo['user_fullname'] = $user->fullname;
        }
        if ($activityLog->type == 'comments') {
            $ticket = Ticket::where('id', $logInfo['ticket_id'])->first();
            unset($logInfo['admin_id']);
            $logInfo['ticket_id'] = $ticket->ticket_id;
        }
        if ($activityLog->type == 'service_providers') {
            $serviceType = ServiceType::where('id', $logInfo['service_type_id'])->first();
            unset($logInfo['service_type_id']);
            $logInfo['service_type'] = $serviceType->service_name;
        }
        if ($activityLog->type == 'sms_email_messages') {
            if ($logInfo['users_id']) {
                $users_id_array = explode(",", $logInfo['users_id']);

                $users = [];
                foreach ($users_id_array as $user_id) {
                    $user = User::where('id', $user_id)->first();
                    $users[] = $user->fullname . '(' . $user->email . ')';
                }
                $user_string = implode(', ', $users);
                $logInfo['users'] = $user_string;
            }
            if ($logInfo['debtors_phone']) {
                $debtors_phone_array = explode(",", $logInfo['debtors_phone']);
                $debtors = [];
                foreach ($debtors_phone_array as $debtor_phone) {
                    $debtor = Transaction::where(DB::raw('CONCAT(debtor_phone_code,debtor_phone_number)'), $debtor_phone)->first();
                    $debtors[] = $debtor->debtor_firstname . $debtor->debtor_lastname . '(' . $debtor_phone . ')';
                }
                $debtors_string = implode(', ', $debtors);
                $logInfo['debtor'] = $debtors_string;
            }
            $logInfo['sent_datetime'] = Carbon::createFromFormat('Y-m-d H:i:s', $logInfo['sent_datetime'], 'UTC')->setTimezone(config('ethiopay.TIMEZONE_STR'))->format('Y-m-d H:i:s');
            unset($logInfo['users_id']);
            unset($logInfo['debtors_phone']);
        }
        if ($activityLog->type == 'admin_user_messages') {
            $user = User::where('id', $logInfo['user_id'])->first();
            unset($logInfo['user_id']);
            unset($logInfo['admin_id']);
            $logInfo['user'] = $user->fullname;
        }
        if ($activityLog->type == 'users') {
            $logInfo['profile_picture'] = "<img class='proimg' src='" . $logInfo['profile_picture_small'] . "'>";

            unset($logInfo['profile_picture_original']);
            unset($logInfo['profile_picture_small']);
            unset($logInfo['profile_picture_medium']);

        }

        return view('admin.activity-log.show', compact('admin', 'logInfo', 'activityLog'));
    }

}
