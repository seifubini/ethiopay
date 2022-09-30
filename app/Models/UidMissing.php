<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UidMissing extends Model {

    protected $table = 'uid_missings';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public function serviceTypeData() {
        return $this->belongsTo('App\Models\ServiceType', 'service_type_id', 'id');
    }
    
    public function UserData() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
