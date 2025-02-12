<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoordinatTPH extends Model
{
    use HasFactory;

    protected $connection = 'mysql_sixth';

    protected $table = 'KoordinatTPH';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'datetime',
        'user_input',
        'estate',
        'afdeling',
        'blok',
        'ancak',
        'tph',
        'lat',
        'lon',
        'app_version'
    ];
}
