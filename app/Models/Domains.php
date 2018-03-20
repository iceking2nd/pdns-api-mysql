<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domains extends Model
{
    public $timestamps = false;
    protected $table = 'domains';

    public function comments()
    {
        return $this->hasMany(\App\Models\Comments::class);
    }

    public function cryptokeys()
    {
        return $this->hasMany(\App\Models\CryptoKeys::class);
    }

    public function metadata()
    {
        return $this->hasMany(\App\Models\DomainMetaData::class);
    }

    public function records()
    {
        return $this->hasMany(\App\Models\Records::class);
    }
}
