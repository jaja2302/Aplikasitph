<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoordinatatTph extends Model
{
    //
    protected $connection = 'mysql2';
    protected $table = 'tph';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;
}
