<?php

namespace App\Http\Controllers\Maps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlokPlot;
use App\Models\Estate;
use App\Models\Afdeling;
use App\Models\Blok;
use Illuminate\Support\Facades\Log;

class GisBlokController extends Controller
{
    //
    public function index()
    {
        // $check_user = is_dep_da();
        // dd($check_user);

        $est = Estate::where('status', '=', 1)->select('id', 'nama')->get()->toArray();

        return view('maps.gisblok', [
            'list_est' => $est
        ]);
    }

    public function getAfdeling(Request $request)
    {
        if (!check_previlege_cmp()) {
            return response()->json([
                'status' => 500,
                'message' => 'Sync failed',
                'error' => "disabled by ADMIN"
            ], 500);
        }

        $estate = $request->input('estate');

        // dd($estate);

        $afdeling = Afdeling::where('dept', $estate)
            ->where('status', '1')
            ->select('id', 'abbr')
            ->distinct()
            ->get()
            ->toArray();

        // dd($afdeling);
        return response()->json($afdeling);
    }


    public function getPlots(Request $request)
    {

        if (!check_previlege_cmp()) {
            return response()->json([
                'status' => 500,
                'message' => 'Sync failed',
                'error' => "disabled by ADMIN"
            ], 500);
        }
        try {
            $estate = $request->input('estate');
            $afdeling = $request->input('afdeling');

            // $namaafd = Afdeling::where('dept', $estate)
            //     ->where('abbr', $afdeling)
            //     ->get();
            // ->pluck('id');
            // dd($namaafd);
            // $
            $namablok = Blok::where('dept', $estate)
                ->where('divisi', $afdeling)
                ->pluck('nama');
            // dd($namablok, $estate, $afdeling);

            $coordinates = BlokPlot::whereIn('nama', $namablok)
                ->get(['lat', 'lon', 'nama'])
                ->groupBy('nama')
                ->map(function ($group) {
                    return $group->map(function ($coord) {
                        return [$coord->lat, $coord->lon];
                    })->values()->all();
                });

            $features = [];
            foreach ($coordinates as $nama => $coords) {
                $features[] = [
                    'type' => 'Feature',
                    'properties' => ['nama' => $nama],
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [$coords]
                    ]
                ];
            }

            return response()->json([
                'plots' => [
                    'type' => 'FeatureCollection',
                    'features' => $features
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getPlots: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to get coordinates: ' . $e->getMessage()
            ], 422);
        }
    }

    public function savePlots(Request $request)
    {

        if (!check_previlege_cmp()) {
            return response()->json([
                'status' => 500,
                'message' => 'Sync failed',
                'error' => "disabled by ADMIN"
            ], 500);
        }
        try {
            $request->validate([
                'nama' => 'required|string',
                'coordinates' => 'required|array|min:3',
            ]);

            $coordinates = collect($request->input('coordinates'))
                ->filter(function ($coord) {
                    return isset($coord['lat']) && isset($coord['lon']) &&
                        is_numeric($coord['lat']) && is_numeric($coord['lon']);
                })
                ->values()
                ->all();

            if (count($coordinates) < 3) {
                throw new \Exception('At least 3 valid coordinates are required');
            }

            // Delete existing coordinates for this plot
            BlokPlot::where('nama', $request->input('nama'))->delete();

            // Insert new coordinates
            $plotsData = [];
            foreach ($coordinates as $coord) {
                $plotsData[] = [
                    'nama' => $request->input('nama'),
                    'lat' => $coord['lat'],
                    'lon' => $coord['lon']
                ];
            }

            BlokPlot::insert($plotsData);

            return response()->json([
                'success' => true,
                'message' => 'Plot coordinates saved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in savePlots: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to save coordinates: ' . $e->getMessage()
            ], 422);
        }
    }
}
