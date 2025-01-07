<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estate extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'estate';

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wil', 'id');
    }

    public function afdelings()
    {
        return $this->hasMany(Afdeling::class, 'estate', 'id');
    }

    public function estate_plots()
    {
        return $this->hasMany(EstatePlot::class, 'est', 'est');
    }
}
