<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlokPlot extends Model
{
    //
    protected $connection = 'mysql4';
    protected $table = 'blok_plot_gis';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;
}
