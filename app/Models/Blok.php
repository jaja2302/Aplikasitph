<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blok extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'blok';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function afdeling()
    {
        return $this->belongsTo(Afdeling::class, 'divisi', 'id');
    }
}
