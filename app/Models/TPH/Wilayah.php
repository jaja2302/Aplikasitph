<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $connection = 'mysql_sixth';

    protected $table = 'wilayah';
    protected $primaryKey = 'Id';

    // Allow mass assignment for these columns
    protected $fillable = [
        'regional',
        'abbr',
        'nama',
        'status',
    ];

    public $timestamps = false;
}
