<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoKeys extends Model
{
    public $timestamps = false;
    protected $table = 'cryptokeys';

    public function domain()
    {
        return $this->belongsTo(\App\Models\Domains::class,'domain_id');
    }
}
