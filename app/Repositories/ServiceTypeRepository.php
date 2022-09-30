<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceType;

class ServiceTypeRepository extends BaseRepository {

    protected $model;

    public function __construct(ServiceType $model) {
        $this->model = $model;
        parent::__construct($this->model);
    }

}
