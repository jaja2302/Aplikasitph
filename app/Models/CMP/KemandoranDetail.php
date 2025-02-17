<?php

namespace App\Models\CMP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KemandoranDetail extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'kemandoran_detail';
    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        'kode_kemandoran',
        'header',
        'kid',
        'nik',
        'nama',
        'tgl_mulai',
        'tgl_akhir',
        'status',
    ];
}
