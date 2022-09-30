<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
    protected $primaryKey = 'id';
    
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $appends = [];
}
