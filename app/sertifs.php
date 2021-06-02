<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sertifs extends Model
{
    protected $fillable = [
        'file','margin_top','margin_left','margin_right','peserta_top','peserta_left','peserta_right','page_two',
    ];
}
