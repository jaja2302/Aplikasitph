<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VersioningDB extends Model
{
    //

    protected $connection = 'mysql3';
    protected $table = 'versioning_db';
    protected $guarded = ['id'];
    public $timestamps = false;
}
