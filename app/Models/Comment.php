<?php

namespace App\Models;

use App\Observers\CommentObserver;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $table = 'comments';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $with = ['user', 'admin'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public static function boot()
    {
        parent::boot();
        static::observe(CommentObserver::class);
    }
}
