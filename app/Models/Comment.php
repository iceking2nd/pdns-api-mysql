<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = false;
    public $fillable = ['domain_id','name','type','modified_at','account','comment'];

    protected $table = 'comments';

    public function domain()
    {
        return $this->belongsTo(\App\Models\Domain::class,'domain_id');
    }
}
