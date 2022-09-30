<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\UidMissing;
use Exception;

class UidMissingController extends Controller {

    public function index() {
        return view("admin.uid-missing.index");
    }

    public function getDatatable(Request $request) {
        $uidMissing = UidMissing::select([
                    'uid_missings.*',
                    DB::raw('CONVERT_TZ(TIMESTAMP(uid_missings.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS created_at_timezone'),
                    DB::raw("service_types.service_name AS service_type_name"),
                    DB::raw("CONCAT(users.firstname, ' ', users.lastname) as user_fullname"),
        ]);
        $uidMissing->join('service_types', function ($join) {
            $join->on('service_types.id', '=', 'uid_missings.service_type_id');
        })->join('users', function ($join) {
            $join->on('users.id', '=', 'uid_missings.user_id');
        });
        $datatables = Datatables::of($uidMissing);
        $datatables->filterColumn('created_at', function ($query, $keyword) {
            $sql = 'CONVERT_TZ(TIMESTAMP(uid_missings.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") like ?';
            $query->whereRaw($sql, ["%{$keyword}%"]);
        });
        $datatables->filterColumn('service_type_name', function ($query, $keyword) {
            $sql = "service_types.service_name like ?";
            $query->whereRaw($sql, ["%{$keyword}%"]);
        });
        $datatables->filterColumn('user_fullname', function ($query, $keyword) {
            $sql = "CONCAT(users.firstname, ' ', users.lastname) like ?";
            $query->whereRaw($sql, ["%{$keyword}%"]);
        });
        return $datatables->make(true);
    }

}
