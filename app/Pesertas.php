<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pesertas extends Model
{
    protected $table = 'pesertas';
    protected $fillable = [
        'id','name','email','partisipan','sertif_id',
    ];
}
