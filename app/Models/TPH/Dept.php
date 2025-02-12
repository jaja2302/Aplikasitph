<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dept extends Model
{
    use HasFactory;

    protected $connection = 'mysql_sixth';

    protected $table = 'dept';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'id_ppro',
        'regional',
        'wilayah',
        'company',
        'abbr',
        'nama',
        'parent',
        'pks',
        'history',
        'status',
    ];

    public $timestamps = false;
}
