<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionCode extends Model
{
    use HasFactory;

    protected $connection = 'mysql_sixth';

    protected $table = 'DivisionCode'; // The table name is 'DivisionCode'
    protected $primaryKey = 'DivisionCode'; // The primary key is 'DivisionCode'

    public $timestamps = false; // Disable timestamps if not required

    protected $fillable = [
        'DivisionCode',
        'DivisionName',
        'BUnitCode',
    ];
}
