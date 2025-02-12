<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldCode extends Model
{
    use HasFactory;
    protected $connection = 'mysql_sixth';

    protected $table = 'FieldCode'; // The table name is 'FieldCode'
    protected $primaryKey = 'FieldCode'; // The primary key is 'FieldCode'

    public $timestamps = false; // Disable timestamps if not required

    protected $fillable = [
        'FieldCode',
        'BUnitCode',
        'DivisionCode',
        'FieldName',
        'FieldNumber',
        'FieldLandArea',
        'PlantingYear',
        'InitialNoOfPlants',
        'PlantsPerHectare',
        'IsMatured',
    ];
}
