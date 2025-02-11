<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlokPlot extends Model
{
    //
    // server cmp plot gis 
    // protected $connection = 'mysql4'; 
    // local tph plot gis 
    protected $connection = 'mysql3';
    protected $table = 'blok_plot_gis';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    public $timestamps = false;
}
