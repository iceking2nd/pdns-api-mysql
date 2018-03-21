<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperMaster extends Model
{
    public $timestamps = false;
    public $fillable = ['ip','nameserver','account'];

    protected $table = 'supermasters';
}
