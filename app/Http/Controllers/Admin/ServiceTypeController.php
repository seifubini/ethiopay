<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Requests\ServiceTypeRequest;
use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use App\Repositories\ParentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Yajra\Datatables\Datatables;

class ServiceTypeController extends Controller
{

    protected $servicetype_repository;

    public function __construct(ParentRepository $repository)
    {
        $this->moduleName = "Service Types";
        $this->moduleRoute = url('admin/service-types');
        $this->moduleView = "admin.service-type";

        View::share('module_name', $this->moduleName);
        View::share('module_route', $this->moduleRoute);
        View::share('moduleView', $this->moduleView);

        $this->servicetype_repository = $repository->ServiceTypeRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->moduleView . ".index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->moduleView . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceTypeRequest $request)
    {

        $input = $request->except('_token');

        try {
            $result = $this->servicetype_repository->store($input);

            if ($result) {
                $request->session()->flash('success', "Service was added succesfully.");
            } else {
                $request->session()->flash('error', "Service was not added.");
            }
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }
        return redirect($this->moduleRoute);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = [];
        try {
            $service = $this->servicetype_repository->getTable()
                ->select(['id', 'service_name as Service type', 
                    DB::raw('CONVERT_TZ(TIMESTAMP(created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") As `Date Time`'),
                    DB::raw("CONCAT(payment_fee_in_percentage, '%') as `Payment Fee`")])
                ->where('id', '=', $id)
                ->first();
            $result['result'] = $service;
            $result['code'] = 200;
        } catch (\Exception $e) {
            $result['code'] = 500;
            $result['message'] = $e->getMessage();
        }

        return Response::json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        try {
            $service = $this->servicetype_repository->getById($id);

            if ($service) {
                return View::make($this->moduleView . ".edit", compact('service'));
            }

            $request->session()->flash('error', "Something went wrong Please try again.");
            return redirect($this->moduleRoute);
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceTypeRequest $request, $id)
    {
        try {
            $inputs = $request->except('_token');

            $isUpdated = $this->servicetype_repository->update($id, $inputs);
            if ($isUpdated) {
                $request->session()->flash('success', "Service was updated successfully.");
                return redirect($this->moduleRoute);
            } else {
                $request->session()->flash('error', "Service was not updated successfully.");
                return redirect($this->moduleRoute);
            }
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
            return redirect($this->moduleRoute);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        return $this->servicetype_repository->destroy($id, $request);
    }

    public function getDatatable(Request $request)
    {

        // return $this->servicetype_repository->getDatatable($request, [
        //             'id',
        //             'service_name',
        //             'created_at',
        //             'deleted_at'
        //                 ]
        // );

        $serviceType = ServiceType::select(['*', DB::raw('CONCAT(payment_fee_in_percentage, "%") as paymentFee'),
            DB::raw('CONVERT_TZ(TIMESTAMP(created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS date')        
            ]);

        return $datatables = Datatables::of($serviceType)
        ->filterColumn('created_at', function ($query, $keyword) {
            $sql = 'CONVERT_TZ(TIMESTAMP(created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") like ?';
            $query->whereRaw($sql, ["%{$keyword}%"]);
        })
        ->make(true);
    }

    public function restore($id)
    {
        return $this->servicetype_repository->restore($id);
    }

}
