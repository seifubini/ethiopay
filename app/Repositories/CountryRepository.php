<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Country;

class CountryRepository extends BaseRepository {

    protected $model;

    public function __construct(Country $model) {
        $this->model = $model;
        parent::__construct($this->model);
    }

    public function getCountryCodeForSelectBox() {

        $phone_codes = [];
        $phone_codes = $this->model->orderBy('phone_code')
                ->groupBy('phone_code')
                ->pluck('phone_code', 'phone_code');

        if (isset($phone_codes) && $phone_codes->count()) {
            $phone_codes = $phone_codes->toArray();
        }
        return $phone_codes;
    }

    public function getCountryForSelectBox() {

        $country = [];
        $country = $this->model->orderBy('name')
                ->pluck('name', 'id');

        if (isset($country) && $country->count()) {
            $country = $country->toArray();
        }

        return $country = $country;
    }

}
