<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\ServiceProviderObserver;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceProvider extends Model {

    use SoftDeletes;

    protected $table = 'service_providers';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $appends = [];
    protected $dates = ['deleted_at'];

    public function serviceTypeData() {
        return $this->belongsTo('App\Models\ServiceType', 'service_type_id', 'id');
    }

    public static function boot() {
        parent::boot();
        static::observe(ServiceProviderObserver::class);
    }
}
