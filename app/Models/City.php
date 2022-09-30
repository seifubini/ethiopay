<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $primaryKey = 'id';
    
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $appends = [];
}
