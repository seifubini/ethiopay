<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\UidLookup;
use App\Models\UidMissing;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\UidMissingServiceAvailabelMail;
use App\Http\Controllers\Admin\Requests\UidLookupRequest;
use App\Http\Controllers\Admin\Requests\UidLookupImportCSVRequest;

class UidLookupController extends Controller {

    public function index() {
        return view("admin.uid-lookup.index");
    }

    public function getDatatable(Request $request) {
        $uidLookup = UidLookup::select([
                    'uid_lookups.*',
                    DB::raw("CONCAT(uid_lookups.debtor_firstname, ' ', uid_lookups.debtor_lastname) as debtor_fullname"),
                    DB::raw("CONCAT('$', FORMAT(uid_lookups.amount, 2)) as amountDisplay"),
                    DB::raw("service_types.service_name AS service_type_name"),
                    DB::raw('CONVERT_TZ(TIMESTAMP(uid_lookups.updated_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS updateDate')
                ])->join('service_types', function ($join) {
            $join->on('service_types.id', '=', 'uid_lookups.service_type_id');
        });
        $datatables = Datatables::of($uidLookup);
        $datatables->filterColumn('debtor_fullname', function ($query, $keyword) {
            $sql = "CONCAT(uid_lookups.debtor_firstname, ' ', uid_lookups.debtor_lastname) like ?";
            $query->whereRaw($sql, ["%{$keyword}%"]);
        });
        $datatables->filterColumn('service_type_name', function ($query, $keyword) {
            $sql = "service_types.service_name like ?";
            $query->whereRaw($sql, ["%{$keyword}%"]);
        });
        $datatables->filterColumn('updated_at', function ($query, $keyword) {
            $sql = "CONVERT_TZ(TIMESTAMP(uid_lookups.updated_at), 'UTC', '" . config('ethiopay.TIMEZONE_STR') . "') like ?";
            $query->whereRaw($sql, ["%{$keyword}%"]);
        });
        $datatables->filterColumn('amount', function ($query, $keyword) {
            $sql = "CONCAT('$', FORMAT(uid_lookups.amount, 2)) like ?";
            $query->whereRaw($sql, ["%{$keyword}%"]);
        });
        return $datatables->make(true);
    }

    public function create() {
        $serviceType = ServiceType::get();
        return view('admin.uid-lookup.create', compact("serviceType"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UidLookupRequest $request) {
        $inputs = $request->except('_token', 'submitBtn');
        $uIdLookupObj = new UidLookup();
        $uIdLookupObj->service_type_id = $inputs['service_type_id'];
        $uIdLookupObj->uid = $inputs['uid'];
        $uIdLookupObj->debtor_firstname = $inputs['debtor_firstname'];
        $uIdLookupObj->debtor_lastname = $inputs['debtor_lastname'];
        $uIdLookupObj->debtor_city = $inputs['debtor_city'];
        $uIdLookupObj->amount = round($inputs['amount'], 2);
        $uIdLookupObj->cut_off_date = $inputs['cut_off_date'];
        $uIdLookupObj->save();

        $uidMissings = UidMissing::with(['serviceTypeData', 'userData'])
                        ->where('service_type_id', $uIdLookupObj->service_type_id)
                        ->where('uid', $uIdLookupObj->uid)->get();
        foreach ($uidMissings as $key => $uidMissing) {
            //return new UidMissingServiceAvailabelMail($uidMissing);
            $emailQueueMessage = (new UidMissingServiceAvailabelMail($uidMissing))->onQueue('emails');
            Mail::to($uidMissing->userData->email, $uidMissing->userData->fullname)->queue($emailQueueMessage);
        }

        $request->session()->flash('success', "UID Lookup added successfully.");
        return redirect(url('admin/uid-lookup'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $uIdLookup = UidLookup::find($id);
        $serviceType = ServiceType::get();
        return view("admin.uid-lookup.edit", compact("uIdLookup", "serviceType"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UidLookupRequest $request, $id) {
        $inputs = $request->except('_token', 'submitBtn');
        $uIdLookupObj = UidLookup::find($id);
        $uIdLookupObj->service_type_id = $inputs['service_type_id'];
        $uIdLookupObj->uid = $inputs['uid'];
        $uIdLookupObj->debtor_firstname = $inputs['debtor_firstname'];
        $uIdLookupObj->debtor_lastname = $inputs['debtor_lastname'];
        $uIdLookupObj->debtor_city = $inputs['debtor_city'];
        $uIdLookupObj->amount = round($inputs['amount'], 2);
        $uIdLookupObj->cut_off_date = $inputs['cut_off_date'];
        $uIdLookupObj->save();

        $uidMissings = UidMissing::with(['serviceTypeData', 'userData'])
                        ->where('service_type_id', $uIdLookupObj->service_type_id)
                        ->where('uid', $uIdLookupObj->uid)->get();
        foreach ($uidMissings as $key => $uidMissing) {
            //return new UidMissingServiceAvailabelMail($uidMissing);
            $emailQueueMessage = (new UidMissingServiceAvailabelMail($uidMissing))->onQueue('emails');
            Mail::to($uidMissing->userData->email, $uidMissing->userData->fullname)->queue($emailQueueMessage);
        }

        $request->session()->flash('success', "UID Lookup updated successfully.");
        return redirect(url('admin/uid-lookup'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request) {
        
    }

    public function importCSV(Request $request) {
        return view("admin.uid-lookup.importCSV");
    }

    public function storeImportCSV(UidLookupImportCSVRequest $request) {
        if ($request->hasFile('uidLookup')) {
            $path = $request->file('uidLookup')->getRealPath();
            $row = 0;
            $uidLookup = array();
            if (($handle = fopen($path, "r")) !== FALSE) {
                $currentUTCdt = Carbon::now('UTC')->toDateTimeString();
                while (($data = fgetcsv($handle, 5000000, ",")) !== FALSE) {
                    try {
                        if ($row == 0) {
                            $row++;
                            continue;
                        }
                        $num = count($data);
                        if ($num == 7) {
                            $uidLookup[$row]['service_type_id'] = $data[0];
                            $uidLookup[$row]['uid'] = $data[1];
                            $uidLookup[$row]['debtor_firstname'] = $data[2];
                            $uidLookup[$row]['debtor_lastname'] = $data[3];
                            $uidLookup[$row]['debtor_city'] = $data[4];
//                        $uidLookup[$row]['debtor_phone'] = $data[3];
                            $uidLookup[$row]['amount'] = round((double) $data[5], 2);
                            $uidLookup[$row]['cut_off_date'] = $data[6];
//                        $uidLookup[$row]['billing_period_start'] = $data[6];
//                        $uidLookup[$row]['billing_period_end'] = $data[7];
//                            $uidLookup[$row]['created_at'] = $currentUTCdt;
//                            $uidLookup[$row]['updated_at'] = $currentUTCdt;

                            $uIdLookupObj = UidLookup::where('service_type_id', $uidLookup[$row]['service_type_id'])
                                            ->where('uid', $uidLookup[$row]['uid'])->first();
                            if ($uIdLookupObj) {
                                $uIdLookupObj->service_type_id = $uidLookup[$row]['service_type_id'];
                                $uIdLookupObj->uid = $uidLookup[$row]['uid'];
                                $uIdLookupObj->debtor_firstname = $uidLookup[$row]['debtor_firstname'];
                                $uIdLookupObj->debtor_lastname = $uidLookup[$row]['debtor_lastname'];
                                $uIdLookupObj->debtor_city = $uidLookup[$row]['debtor_city'];
                                $uIdLookupObj->amount = $uidLookup[$row]['amount'];
                                $uIdLookupObj->cut_off_date = $uidLookup[$row]['cut_off_date'];
                                $uIdLookupObj->created_at = $currentUTCdt;
                                $uIdLookupObj->updated_at = $currentUTCdt;
                                $uIdLookupObj->save();
                            } else {
                                $uIdLookupObj = new UidLookup();
                                $uIdLookupObj->service_type_id = $uidLookup[$row]['service_type_id'];
                                $uIdLookupObj->uid = $uidLookup[$row]['uid'];
                                $uIdLookupObj->debtor_firstname = $uidLookup[$row]['debtor_firstname'];
                                $uIdLookupObj->debtor_lastname = $uidLookup[$row]['debtor_lastname'];
                                $uIdLookupObj->debtor_city = $uidLookup[$row]['debtor_city'];
                                $uIdLookupObj->amount = $uidLookup[$row]['amount'];
                                $uIdLookupObj->cut_off_date = $uidLookup[$row]['cut_off_date'];
                                $uIdLookupObj->created_at = $currentUTCdt;
                                $uIdLookupObj->updated_at = $currentUTCdt;
                                $uIdLookupObj->save();

                                $uidMissings = UidMissing::with(['serviceTypeData', 'userData'])->where('service_type_id', $uIdLookupObj->service_type_id)
                                                ->where('uid', $uIdLookupObj->uid)->get();
                                foreach ($uidMissings as $key => $uidMissing) {
                                    //return new UidMissingServiceAvailabelMail($uidMissing);
                                    $emailQueueMessage = (new UidMissingServiceAvailabelMail($uidMissing))->onQueue('emails');
                                    Mail::to($uidMissing->userData->email, $uidMissing->userData->fullname)->queue($emailQueueMessage);
                                }
                            }
                        }
                    } catch (Exception $e) {
                        
                    }
                    $row++;
                }
                fclose($handle);
                //array_shift($uidLookup);
            }
            if (count($uidLookup)) {
//                UidLookup::query()->insert($uidLookup);
                addToLog('UID Lookup CSV file imported successfully.', 'uid_lookups', json_encode($uidLookup));

                $request->session()->flash('success', 'CSV file imported successfully.');
                return redirect('admin/uid-lookup');
            }
            //$request->session()->flash('errorAlert', 'Data not found.');
            return redirect('admin/uid-lookup/import-csv')->withErrors([
                        'uidLookup' => [
                            'Data not found in file.'
                        ]
            ]);
        }
        //$request->session()->flash('errorAlert', 'Data not found.');
        return redirect('admin/uid-lookup/import-csv')->withErrors([
                    'uidLookup' => [
                        'Data not found in file.'
                    ]
        ]);
    }

}
