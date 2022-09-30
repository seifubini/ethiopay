<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $primaryKey = 'id';
    
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $appends = [];
    
    public function countryData() {
        return $this->hasOne('App\Models\Country', 'id', 'country_id');
    }
    
    public function stateData() {
        return $this->hasOne('App\Models\State', 'id', 'state_id');
    }
    
    public function cityData() {
        return $this->hasOne('App\Models\City', 'id', 'city_id');
    }
}
