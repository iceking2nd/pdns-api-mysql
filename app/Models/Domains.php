<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domains extends Model
{
    public $timestamps = false;
    protected $table = 'domains';

    public function comments()
    {
        return $this->hasMany(\App\Models\Comments::class,'domain_id');
    }

    public function cryptokeys()
    {
        return $this->hasMany(\App\Models\CryptoKeys::class,'domain_id');
    }

    public function metadata()
    {
        return $this->hasMany(\App\Models\DomainMetaData::class,'domain_id');
    }

    public function records()
    {
        return $this->hasMany(\App\Models\Records::class,'domain_id');
    }
}
