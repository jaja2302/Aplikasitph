<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoordinatatTph extends Model
{
    //untuk server cmp mysql2
    // protected $connection = 'mysql2';
    //untuk local tph mysql3
    protected $connection = 'mysql3';
    protected $table = 'tph';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    // // Hanya perlu menambahkan casts untuk handling tipe data
    // protected $casts = [
    //     'app_version' => 'array',
    //     'lat' => 'float',
    //     'lon' => 'float'
    // ];
}
