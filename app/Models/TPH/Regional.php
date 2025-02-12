<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regional extends Model
{
    use HasFactory;

    protected $connection = 'mysql_sixth';

    protected $table = 'regional';
    protected $primaryKey = 'Id';

    // Allow mass assignment for these columns
    protected $fillable = [
        'abbr',
        'nama',
        'status',
    ];

    public $timestamps = false;
}
