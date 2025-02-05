<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estate extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'dept';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah', 'id');
    }

    public function afdelings()
    {
        return $this->hasMany(Afdeling::class, 'estate', 'id');
    }

    public function estate_plots()
    {
        return $this->hasMany(EstatePlot::class, 'estate', 'id');
    }
}
