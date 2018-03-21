<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoKey extends Model
{
    public $timestamps = false;
    public $fillable = ['domain_id','flags','active','content'];

    protected $table = 'cryptokeys';

    public function domain()
    {
        return $this->belongsTo(\App\Models\Domain::class,'domain_id');
    }
}
