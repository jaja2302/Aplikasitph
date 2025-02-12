<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TPH extends Model
{
    use HasFactory;

    protected $connection = 'mysql_sixth';

    protected $table = 'TPH';
    
    public $timestamps = false;

    protected $fillable = [
        'id',
         'CompanyCode',
        'Regional',
        'BUnitCode',
        'DivisionCode',
        'FieldCode',
        'planting_year',
        'ancak',
        'tph',
        'lat',
        'lon',
        'user_input',
        'app_version',
        'date_modified'
    ];
}
