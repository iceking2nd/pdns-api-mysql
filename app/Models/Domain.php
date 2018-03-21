<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    public $timestamps = false;
    public $fillable = ['name', 'master', 'last_check', 'type', 'notified_serial', 'account'];

    protected $table = 'domains';

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class,'domain_id');
    }

    public function cryptokeys()
    {
        return $this->hasMany(\App\Models\CryptoKey::class,'domain_id');
    }

    public function metadata()
    {
        return $this->hasMany(\App\Models\DomainMetaData::class,'domain_id');
    }

    public function records()
    {
        return $this->hasMany(\App\Models\Record::class,'domain_id');
    }
}
