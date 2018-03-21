<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    public $timestamps = false;
    public $fillable = ['domain_id', 'name', 'type', 'content', 'ttl', 'prio', 'change_date', 'disabled', 'ordername', 'auth'];

    protected $table = 'records';

    public function domain()
    {
        return $this->belongsTo(\App\Models\Domain::class,'domain_id');
    }
}
