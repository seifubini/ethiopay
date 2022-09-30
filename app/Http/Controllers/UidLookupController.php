<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UidLookup;
use App\Models\UidMissing;

class UidLookupController extends Controller {

    public function checkUidLookup(Request $request) {
        $checkUidLookupRes = array();

        $customer_service_number = $request->get('customer_service_number');
        $service_type_id = $request->get('service_type_id');
        
        if ($service_type_id) {
            $isUidLookupExists = UidLookup::select([
                        '*',
                        DB::raw('id AS uid_lookup_id'),
                        DB::raw('DATE_FORMAT(cut_off_date, "%M %d") AS cut_off_date_formated'),
                            //DB::raw('DATE_FORMAT(billing_period_start, "%M %d") AS billing_period_start_formated'),
                            //DB::raw('DATE_FORMAT(billing_period_end, "%M %d") AS billing_period_end_formated')
                    ])
                    ->where('uid', $customer_service_number)
                    ->where('service_type_id', $service_type_id)
                    ->first();

            if ($isUidLookupExists) {
                $checkUidLookupRes['status'] = 'true';
                $checkUidLookupRes['message'] = '';
                $checkUidLookupRes['record'] = $isUidLookupExists;
            } else {
                $checkUidLookupRes['status'] = 'false';
                $checkUidLookupRes['message'] = 'Please enter valid UID.';
                
                $user_id = auth()->guard('web')->user()->id;
                $uIdMissingExists = UidMissing::where('service_type_id', $service_type_id)
                        ->where('user_id', $user_id)
                        ->where('uid', $customer_service_number)
                        ->exists();
                if(!$uIdMissingExists){
                    $uIdMissing = new UidMissing();
                    $uIdMissing->service_type_id = $service_type_id;
                    $uIdMissing->user_id = $user_id;
                    $uIdMissing->uid = $customer_service_number;
                    $uIdMissing->save();
                }
            }
            return response()->json($checkUidLookupRes);
        } else {
            $checkUidLookupRes['status'] = 'false';
            $checkUidLookupRes['message'] = 'Please select service type.';
            return response()->json($checkUidLookupRes);
        }
    }

}
