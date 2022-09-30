<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Address;

class AddressRepository extends BaseRepository {

    protected $model;

    public function __construct(Address $model) {
        $this->model = $model;
        parent::__construct($this->model);
    }

}
