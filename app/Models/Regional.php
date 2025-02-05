<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regional extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'regional';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public $timestamps = false;

    public function wilayahs()
    {
        return $this->hasMany(Wilayah::class, 'regional', 'id');
    }
}
