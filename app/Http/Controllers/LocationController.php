<?php

namespace App\Http\Controllers;

use App\Models\Regional;
use App\Models\Wilayah;
use App\Models\Estate;
use App\Models\Afdeling;
use App\Models\Blok;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getRegional()
    {
        return response()->json(Regional::all());
    }

    public function getWilayah($regionalId)
    {
        return response()->json(Wilayah::where('regional', $regionalId)->get());
    }

    public function getEstate($wilayahId)
    {
        return response()->json(Estate::where('wilayah', $wilayahId)
            ->where('status', '=', '1')
            ->orderBy('abbr', 'asc')
            ->get());
    }

    public function getAfdeling($estateId)
    {
        return response()->json(
            Afdeling::where('dept', $estateId)
                ->where('status', '=', '1')
                ->orderBy('abbr', 'asc')
                ->get()
        );
    }


    public function getBlok($afdelingId)
    {
        return response()->json(Blok::where('divisi', $afdelingId)->get());
    }
}
