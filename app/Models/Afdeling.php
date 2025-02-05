<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Afdeling extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'divisi';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;



    public function estate()
    {
        return $this->belongsTo(Estate::class, 'dept', 'id');
    }



    public function bloks()
    {
        return $this->hasMany(Blok::class, 'afdeling', 'id');
    }
}
