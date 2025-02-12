<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TPHNew extends Model
{
    use HasFactory;

    protected $connection = 'mysql_sixth'; // untuk database lama
    // protected $connection = 'mysql_eighth'; // untuk database baru

    protected $table = 'tph';
    protected $primaryKey = 'id';

    // Allow mass assignment for these columns
    protected $fillable = [
        'regional',
        'company',
        'company_ppro',
        'company_abbr',
        'company_nama',
        'dept',
        'dept_ppro',
        'dept_abbr',
        'dept_nama',
        'divisi',
        'divisi_ppro',
        'divisi_abbr',
        'divisi_nama',
        'blok',
        'blok_kode',
        'blok_nama',
        'ancak',
        'kode_tph',
        'nomor',
        'tahun',
        'lat',
        'lon',
        'user_input',
        'app_version',
        'create_by',
        'create_nama',
        'create_date',
        'update_by',
        'update_nama',
        'update_date',
        'history',
        'status',
    ];

    public $timestamps = false;
}
