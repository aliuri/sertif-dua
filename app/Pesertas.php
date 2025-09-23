<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesertas extends Model
{
    // use HasFactory;

    protected $table = 'pesertas';
    protected $fillable = [
        'id','name','email','partisipan','sertif_id',
    ];

    // Relasi ke tabel sertif
    public function sertif()
    {
        return $this->belongsTo(sertifs::class, 'sertif_id');
    }
}
