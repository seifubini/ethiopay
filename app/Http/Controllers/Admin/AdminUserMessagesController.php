<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\Datatables\Datatables;
use App\library\CommonFunction;
use App\library\TwilioLibrary;
use App\Models\AdminUserMessage;
use App\Models\User;

class AdminUserMessagesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("admin.admin-user-message.index");
    }

    public function getDatatable(Request $request) {
        $adminUserMessageSubQuery = AdminUserMessage::select([
                    'admin_user_messages.*',
                ])->whereIn('admin_user_messages.id', function($query) {
                    $query->select([
                                DB::raw("max(admin_user_messages2.id) AS maxMsgId")
                            ])
                            ->from('admin_user_messages AS admin_user_messages2')
                            ->groupBy('admin_user_messages2.user_id');
                })->join('users', function ($join) {
            $join->on('users.id', '=', 'admin_user_messages.user_id');
        });

        $uidMissing2 = DB::table(DB::raw("({$adminUserMessageSubQuery->toSql()}) as adminUserMessageSubQuery"))
                        ->rightJoin('users AS users2', function ($join) {
                            $join->on('users2.id', '=', 'adminUserMessageSubQuery.user_id');
                        })->select([
                    'adminUserMessageSubQuery.*', 'users2.email', 'users2.id as userId',
                    DB::raw("CONCAT(users2.firstname, ' ', users2.lastname) as user_fullname"),
                    DB::raw('CONVERT_TZ(TIMESTAMP(adminUserMessageSubQuery.updated_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS updated_at_timezone'),
                ])->where('users2.deleted_at', null);

        $datatables = Datatables::of($uidMissing2);
        $datatables->addColumn('msgTotalCount', function ($messsage) {
            $adminUserMessage = AdminUserMessage::select([
                        DB::raw("COUNT(id) AS msgTotalCount")
                    ])
                    ->where('user_id', $messsage->user_id)
                    ->first();
            if ($adminUserMessage)
                return $adminUserMessage->msgTotalCount;
            else
                return 0;
        });

        $datatables->filterColumn('user_fullname', function ($query, $keyword) {
            $sql = "CONCAT(users2.firstname, ' ', users2.lastname) like ?";
            $query->whereRaw($sql, ["%{$keyword}%"]);
        });
        $datatables->filterColumn('updated_at', function ($query, $keyword) {
            $sql = "CONVERT_TZ(TIMESTAMP(adminUserMessageSubQuery.updated_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "') like ?";
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $input = $request->all();
        $rules = config('input_validation_admin.rules.AdminUserMessage.create');
        $messages = config('input_validation_admin.messages.AdminUserMessage.create');

        $apiValidator = CommonFunction::inputValidator($input, $rules, $messages);
        if ($apiValidator) {
            return $apiValidator;
        }
        
        $user = User::findOrFail($input['user_id']);
        $twilioLibraryRes = TwilioLibrary::SendSMS($user->phone_code . $user->phone_number, $input['message']);
        if(!$twilioLibraryRes){
            $data = array('status' => false, 'message' => 'Something went wrong.');
            return response()->json($data);
        }
        
        $adminUserMessage = new AdminUserMessage();
        $adminUserMessage->user_id = $input['user_id'];
        $adminUserMessage->admin_id = auth()->guard('admin')->user()->id;
        $adminUserMessage->sent_by = 'admin';
        $adminUserMessage->message = $input['message'];
        $adminUserMessage->save();
        
        $data = array('status' => true, 'message' => 'Message has been send successfully.');
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id) {
        $user = User::findOrFail($user_id);
        $adminUserMessages = AdminUserMessage::select([
            'admin_user_messages.*',
            DB::raw("CONVERT_TZ(TIMESTAMP(admin_user_messages.updated_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "') AS updated_at_timezone")
        ])->where('user_id', $user_id)->orderBy('updated_at')->get();
        
        $viewData = [
            'user' => $user,
            'adminUserMessages' => $adminUserMessages
        ];

        return view("admin.admin-user-message.viewDetail", $viewData);
    }
    
    public function loadNewMessages(Request $request) {
        $user_id = $request->get('user_id');
        $last_message_id = $request->get('last_message_id');
        
        $adminUserMessages = AdminUserMessage::with(['adminData'])->select([
            'admin_user_messages.*',
            DB::raw("CONVERT_TZ(TIMESTAMP(admin_user_messages.updated_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "') AS updated_at_timezone")
        ])->where('user_id', $user_id);
        
        if($last_message_id){
            $adminUserMessages->where('id', '>', $last_message_id);
        }
        $adminUserMessagesRes = $adminUserMessages->orderBy('updated_at')->get();
        
        $responseData = [
            'status' => true,
            'message' => 'Message get successfully.',
            'adminUserMessages' => $adminUserMessagesRes
        ];

        return response()->json($responseData);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
