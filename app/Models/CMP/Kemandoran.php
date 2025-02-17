<?php

namespace App\Models\CMP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kemandoran extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';

    protected $table = 'kemandoran';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
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
        'kode',
        'nama',
        'type',
        'create_by',
        'create_nama',
        'create_date',
        'update_by',
        'update_nama',
        'update_date',
        'history',
        'status',
    ];
}
