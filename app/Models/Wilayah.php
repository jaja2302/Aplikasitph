<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'wilayah';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'regional', 'id');
    }

    public function estates()
    {
        return $this->hasMany(Estate::class, 'wilayah', 'id');
    }
}
