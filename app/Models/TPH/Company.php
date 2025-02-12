<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $connection = 'mysql_sixth';

    protected $table = 'company';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'id_ppro',
        'regional',
        'regional_nama',
        'abbr',
        'nama',
        'business_unit',
        'logo',
        'grup',
        'alamat',
        'history',
        'journal',
        'pr_workflow',
        'pr_apporoval',
        'po_sign',
        'gr_approval',
        'status',
    ];

    public $timestamps = false;
}
