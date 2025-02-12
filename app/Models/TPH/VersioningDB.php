<?php

namespace App\Models\TPH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersioningDB extends Model
{
    use HasFactory;

    protected $connection = 'mysql_sixth';

    protected $table = 'versioning_db';
    protected $primaryKey = 'id';

    // Allow mass assignment for these columns
    protected $fillable = [
        'table_name',
        'date_modified',
    ];

    public $timestamps = false;
}
