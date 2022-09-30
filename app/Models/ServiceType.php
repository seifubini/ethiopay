<?php

namespace App\Models;

use App\Observers\ServiceTypeObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceType extends Model {

    use SoftDeletes;

    protected $table = 'service_types';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $appends = [];
    protected $dates = ['deleted_at'];

    /**
     * Get the service providers of service type.
     */
    public function serviceProvidersData()
    {
        return $this->hasMany('App\Models\ServiceProvider');
    }

    public static function boot() {
        parent::boot();
        static::observe(ServiceTypeObserver::class);
    }
}
