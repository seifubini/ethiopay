<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\SmsEmailMessageObserver;

class SmsEmailMessage extends Model
{
    protected $table = 'sms_email_messages';
    protected $primaryKey = 'id';
    
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $appends = [];

    public static function boot() {
        parent::boot();
        static::observe(SmsEmailMessageObserver::class);
    }
}
