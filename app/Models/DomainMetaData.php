<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainMetaData extends Model
{
    public $timestamps = false;
    public $fillable = ['domain_id','kind','content'];

    protected $table = 'domainmetadata';

    public function domain()
    {
        return $this->belongsTo(\App\Models\Domain::class,'domain_id');
    }
}
