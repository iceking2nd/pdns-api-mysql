<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    public $timestamps = false;
    protected $table = 'comments';

    public function domain()
    {
        return $this->belongsTo(\App\Models\Domains::class,'domain_id');
    }
}
