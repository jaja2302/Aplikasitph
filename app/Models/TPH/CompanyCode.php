<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCode extends Model
{
    use HasFactory;
    protected $connection = 'mysql_sixth';
    protected $table = 'CompanyCode';
    protected $primaryKey = 'CompanyCode'; // The primary key

    public $timestamps = false; // Disable timestamps if not required

    protected $fillable = [
        'CompanyCode',
        'CompanyName',
    ];
}
