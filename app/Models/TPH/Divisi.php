<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;


    protected $connection = 'mysql_sixth';

    protected $table = 'divisi';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'id',
        'id_ppro',
        'company',
        'dept',
        'abbr',
        'nama',
        'status',
    ];

    public $timestamps = false;
}
