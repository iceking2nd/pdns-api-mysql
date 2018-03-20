<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Records extends Model
{
    public $timestamps = false;
    protected $table = 'records';

    public function domain()
    {
        return $this->belongsTo(\App\Models\Domains::class,'domain_id');
    }
}
