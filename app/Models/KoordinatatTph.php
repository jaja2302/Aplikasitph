<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoordinatatTph extends Model
{
    //
    protected $table = 'tph';
    // protected $fillable = ['key', 'coordinates'];
    protected $guarded = ['id'];
    public $timestamps = false;
}
