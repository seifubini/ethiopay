<?php

namespace App\Models;

use App\Observers\SettingObserver;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $appends = [];

    public static function boot() {
        parent::boot();
        static::observe(SettingObserver::class);
    }
}
