<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Config;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
//use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Repositories\ParentRepository;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Admin\Requests\ServiceProviderRequest;

class ServiceProviderController extends Controller {

    protected $serviceprovidor_repository;
    protected $service_repository;

    public function __construct(ParentRepository $repository) {
        $this->moduleName = "Service Providers";
        $this->moduleRoute = url('admin/service-providers');
        $this->moduleView = "admin.service-provider";

        View::share('module_name', $this->moduleName);
        View::share('module_route', $this->moduleRoute);
        View::share('moduleView', $this->moduleView);

        $this->serviceprovidor_repository = $repository->ServiceProviderRepository();
        $this->service_repository = $repository->ServiceTypeRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view($this->moduleView . ".index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $service = $this->service_repository->getTable()->pluck('service_name', 'id');
        if (count($service) > 0) {
            $service = $service->toArray();
        }

        $service = array('' => 'Select service type') + $service;

        return view($this->moduleView . '.create', compact('service'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceProviderRequest $request) {

        $input = $request->except('_token');

        try {
            $result = $this->serviceprovidor_repository->store($input);

            if ($result) {               
                $request->session()->flash('success', "Service provider was added succesfully.");
            } else {
                $request->session()->flash('error', "Service provider was not added.");
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
    public function show($id) {
        $result = [];
        try {
            $service = $this->serviceprovidor_repository->getTable()
                    ->select(['service_providers.id', 'service_types.service_name as Service type', 'service_providers.service_id as Service id', 'service_providers.provider_name as Provider', 
                        DB::raw('CONVERT_TZ(TIMESTAMP(service_providers.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") As `Date Time`'),
                        ])
                    ->join('service_types', 'service_types.id', '=', 'service_providers.service_type_id')
                    ->where('service_providers.id', '=', $id)
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
    public function edit($id, Request $request) {
        try {
            $service_provider = $this->serviceprovidor_repository->getById($id);

            if ($service_provider) {

                $service = $this->service_repository->getTable()->pluck('service_name', 'id');

                if (count($service) > 0) {
                    $service = $service->toArray();
                }
                $service = array('' => 'Select service type') + $service;

                return View::make($this->moduleView . ".edit", compact('service', 'service_provider'));
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
    public function update(ServiceProviderRequest $request, $id) {
        try {
            $inputs = $request->except('_token');

            $isUpdated = $this->serviceprovidor_repository->update($id, $inputs);

            if ($isUpdated) {
                $request->session()->flash('success', "Service provider was updated successfully.");
                return redirect($this->moduleRoute);
            } else {
                $request->session()->flash('error', "Service provider was not updated successfully.");
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
    public function destroy($id, Request $request) {
        return $this->serviceprovidor_repository->destroy($id, $request);
    }

    public function getDatatable(Request $request) {

        $result = '';
        $result = $this->serviceprovidor_repository->getTable()->select([
                    'service_providers.id',
                    'service_types.service_name',
                    'service_providers.service_id',
                    'service_providers.provider_name',
                    'service_providers.created_at',
                    'service_providers.deleted_at',
                    DB::raw('CONVERT_TZ(TIMESTAMP(service_providers.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS date')        
                    
                ])
                ->join('service_types', 'service_types.id', '=', 'service_providers.service_type_id');

        return Datatables::of($result)
            ->filterColumn('service_providers.created_at', function ($query, $keyword) {
                $sql = 'CONVERT_TZ(TIMESTAMP(service_providers.created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") like ?';
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })->make(true);
    }

    public function restore($id) {
        return $this->serviceprovidor_repository->restore($id);
    }

}
