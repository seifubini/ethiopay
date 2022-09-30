<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class SettingRepository extends BaseRepository {

    protected $model;

    public function __construct(Setting $model) {
        $this->model = $model;
        parent::__construct($this->model);
    }

}
