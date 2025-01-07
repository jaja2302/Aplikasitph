<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatePlot extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'estate_plot';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function estate()
    {
        return $this->belongsTo(Estate::class, 'est', 'est');
    }
}
