<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\AdminUserMessageObserver;

class AdminUserMessage extends Model {

    protected $table = 'admin_user_messages';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public function UserData() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    
    public function AdminData() {
        return $this->belongsTo('App\Models\Admin', 'admin_id', 'id');
    }
    
    public function getMessageAttribute($value) {
        return nl2br($value);
    }

    public static function boot() {
        parent::boot();
        static::observe(AdminUserMessageObserver::class);
    }
}
