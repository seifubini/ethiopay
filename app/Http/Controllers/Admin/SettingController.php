<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Config;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Repositories\ParentRepository;
//use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Admin\Requests\SettingRequest;

class SettingController extends Controller {

    protected $repository;

    public function __construct(ParentRepository $repository) {
        $this->moduleName = "Settings";
        $this->moduleRoute = url('admin/settings');
        $this->moduleView = "admin.setting";

        View::share('module_name', $this->moduleName);
        View::share('module_route', $this->moduleRoute);
        View::share('moduleView', $this->moduleView);
        $this->repository = $repository;
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
//        return view($this->moduleView . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingRequest $request) {

        $input = $request->except('_token');

        try {
            $result = $this->repository->SettingRepository()->store($input);

            if ($result) {
                $request->session()->flash('success', "Setting was added succesfully.");
            } else {
                $request->session()->flash('error', "Setting was not added.");
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
            $service = $this->repository->SettingRepository()->getTable()
                    ->select(['id', 'key', 'value', 'description', 
                            DB::raw('CONVERT_TZ(TIMESTAMP(created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") As DateTime')
                        ])
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
    public function edit($id, SettingRequest $request) {
        try {
            $setting = $this->repository->SettingRepository()->getById($id);

            if ($setting) {
                return View::make($this->moduleView . ".edit", compact('setting'));
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
    public function update(SettingRequest $request, $id) {
        try {
            $inputs = $request->except('_token');

            $isUpdated = $this->repository->SettingRepository()->update($id, $inputs);
            if ($isUpdated) {
                $request->session()->flash('success', "Setting was updated successfully.");
                return redirect($this->moduleRoute);
            } else {
                $request->session()->flash('error', "Setting was not updated successfully.");
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
        return $this->repository->SettingRepository()->destroy($id, $request);
    }

    public function getDatatable(Request $request) {

        $result = $this->repository->SettingRepository()->getTable()->select([
            'id',
            'key',
            'value',
            DB::raw('CONVERT_TZ(TIMESTAMP(created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") AS date')
        ]);

        return Datatables::of($result)
            ->filterColumn('created_at', function ($query, $keyword) {
                $sql = 'CONVERT_TZ(TIMESTAMP(created_at), "UTC", "' . config('ethiopay.TIMEZONE_STR') . '") like ?';
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })->make(true);
    }

}
