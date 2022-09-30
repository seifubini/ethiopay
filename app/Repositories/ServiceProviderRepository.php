<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceProvider;

class ServiceProviderRepository extends BaseRepository {

    protected $model;

    public function __construct(ServiceProvider $model) {
        $this->model = $model;
        parent::__construct($this->model);
    }

}
