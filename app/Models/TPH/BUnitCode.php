<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BUnitCode extends Model
{
    use HasFactory;
    protected $connection = 'mysql_sixth';
    protected $table = 'BUnitCode'; // The table name is 'BUnitCode'
    protected $primaryKey = 'BUnitCode'; // The primary key is 'BUnitCode'

    public $timestamps = false; // Disable timestamps if not required

    protected $fillable = [
        'BUnitCode',
        'BUnitName',
        'CompanyCode',
    ];
}
