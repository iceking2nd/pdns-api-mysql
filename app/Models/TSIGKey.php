<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TSIGKey extends Model
{
    public $timestamps = false;
    public $fillable = ['name','algorithm','secret'];

    protected $table = 'tsigkeys';
}
