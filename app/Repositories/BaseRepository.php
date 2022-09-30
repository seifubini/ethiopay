<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\File;
//use Yajra\DataTables\Facades\DataTables;
use Yajra\Datatables\Datatables;

class BaseRepository {

    /**
     * The Model name.
     *
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;
    private static $instance;

    public function __construct($model) {
        $this->model = $model;
    }

    // getInstance method 
    public function getInstance() {

        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Paginate the given query.
     *
     * @param The number of models to return for pagination $n integer
     *
     * @return mixed
     */
    public function getPaginate($n) {
        return $this->model->paginate($n);
    }

    /**
     * Create a new model and return the instance.
     *
     * @param array $inputs
     *
     * @return Model instance
     */
    public function store(array $inputs) {
        return $this->model->create($inputs);
    }

    /**
     * insert a new model and return the instance.
     *
     * @param array $inputs
     *
     * @return Model instance
     */
    public function insert(array $inputs) {
        return $this->model->insert($inputs);
    }

    public function getById($id) {
        return $this->model->FindOrFail($id);
    }

    /**
     * Update the model in the database.
     *
     * @param $id
     * @param array $inputs
     */
    public function update($id, array $inputs) {
        return $this->model->find($id)->update($inputs);
    }

    /**
     * Delete the model from the database.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function destroy($id, $request, $delete_path = [], $image_name = "") {

        $result = array();

        try {
            $table = $this->model->withTrashed()->find($id);

            if ($request->input('deleteType') == 1) {
                $res = $table->forceDelete();

                if (is_array($delete_path) && count($delete_path) > 0 && $image_name != "") {
                    for ($i = 0; $i < count($delete_path); $i++) {
                        if (File::exists($delete_path[$i] . '' . $image_name))
                            File::delete($delete_path[$i] . '' . $image_name);
                    }
                }
                else if ($image_name != "") {
                    if (File::exists($delete_path . '' . $image_name))
                        File::delete($delete_path . '' . $image_name);
                }
            } else {
                $res = $table->delete();
            }


            if ($table) {
                $result['message'] = "Record was deleted succefully.";
                $result['code'] = 200;
            } else {
                $result['code'] = 400;
                $result['message'] = "Record was not deleted.";
            }
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
            $result['code'] = 400;
        }

        return response()->json($result, $result['code']);
    }

    public function getAll() {
        return $this->model->get();
    }

    function getDatatable($request, $field = '*', $where = "") {
        $result = '';
        $result = $this->model->select($field);

        if ($where != "")
            $result->where($where);

        return Datatables::of($result)->make(true);
    }

    public function getTable() {
        return $this->model;
    }

    public function restore($id) {
        $result = array();
        try {
            $restore = $this->model->onlyTrashed()->find($id)->restore();
            if ($restore) {
                $result['message'] = "Record was restored successfully.";
                $result['code'] = 200;
            } else {
                $result['code'] = 400;
                $result['message'] = "Record was not restored.";
            }
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
            $result['code'] = 400;
        }

        return response()->json($result, $result['code']);
    }

}
