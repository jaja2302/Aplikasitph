<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blok extends Model
{
    use HasFactory;

    protected $connection = 'mysql_sixth';

    protected $table = 'blok';
    protected $primaryKey = 'Id';

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
        'nama',
        'kode',
        'kode_pmmp',
        'ancak',
        'jumlah_tph',
        'luas_area',
        'tahun',
        'jumlah_pokok',
        'pkok_per_hektar',
        'is_mature',
        'create_by',
        'create_nama',
        'create_date',
        'update_by',
        'update_name',
        'update_date',
        'history',
        'status'
    ];

    public $timestamps = false;
}
