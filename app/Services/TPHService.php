<?php

namespace App\Services;

use App\Models\KoordinatatTph;
use App\Models\Estate;
use App\Models\Afdeling;
use App\Models\Blok;
use App\Models\BlokPlot;
use App\Models\Regional;
use App\Models\Wilayah;

class TPHService
{
    private const AFD_PREFIX = 'AFD-';
    private const DEFAULT_GEOJSON = ['type' => 'FeatureCollection', 'features' => []];

    private function getBaseTPHQuery($est, $afdKey, $type, $blokName = null)
    {
        $query = KoordinatatTph::where('dept', $est)
            ->where('divisi', $afdKey);
        // 112 // app\Services\TPHService.php:91
        // 87 // app\Services\TPHService.php:91
        switch ($type) {
            case 'valid':
                if ($blokName) {
                    $query->where('blok_kode', $blokName);
                }
                return $query->where('status', 1)
                    ->whereNotNull('lat')
                    ->where('lat', '!=', '')
                    ->where('lat', '!=', '-');
            case 'unverified':
                if ($blokName) {
                    $query->where('blok_kode', $blokName);
                }
                return $query->where(function ($q) {
                    $q->where('status', '!=', 1)
                        ->orWhereNull('status');
                });
            default:
                if ($blokName) {
                    $query->where('blok_kode', $blokName);
                }
                return $query;
        }
    }

    private function tphToGeoJsonFeature($point)
    {
        return [
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [$point->lon, $point->lat]
            ],
            'properties' => [
                'id' => $point->id,
                'estate' => $point->dept_abbr,
                'afdeling' => $point->divisi_abbr,
                'blok' => $point->blok_kode,
                'ancak' => $point->ancak,
                'user_input' => $point->user_input,
                'tph' => $point->nomor,
                'status' => $point->status,
            ]
        ];
    }

    public function getBlokTersidak($est, $afdKey)
    {
        return $this->getBaseTPHQuery($est, $afdKey, 'valid')
            ->pluck('blok_nama')
            ->unique()
            ->values()
            ->toArray();
    }

    public function getPlotMap($afdelingId, $selectedBlokNama = null)
    {
        $blok = Blok::where('divisi', $afdelingId)->get();
        $name_blok = $blok->pluck('nama');

        $query = BlokPlot::whereIn('nama', $name_blok);

        // Filter by selected blok if provided
        if ($selectedBlokNama) {
            $query->where('nama', $selectedBlokNama);
        }

        $bloks = $query->get()->groupBy('nama');

        $afdeling = Afdeling::find($afdelingId);
        $estate = Estate::find($afdeling->dept);

        $blokTersidak = $this->getBlokTersidak($estate->id, $afdeling->id);

        $features = $bloks->map(function ($blokGroup, $nama) use ($blokTersidak) {
            $coordinates = $blokGroup->map(function ($blok) {
                return [$blok->lon, $blok->lat];
            })->toArray();

            $firstBlok = $blokGroup->first();
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [$coordinates]
                ],
                'properties' => [
                    'id' => $firstBlok->id,
                    'nama' => $nama,
                    'tersidak' => in_array($nama, $blokTersidak)
                ]
            ];
        })->values()->toArray();

        return [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
    }

    public function getTPHCoordinates($estateId, $afdelingId, $selectedBlokNama = null)
    {
        $tphQuery = $this->getBaseTPHQuery($estateId, $afdelingId, 'valid');

        // Filter by selected blok if provided
        if ($selectedBlokNama) {
            $tphQuery->where('blok_nama', $selectedBlokNama);
        }

        $tphPoints = $tphQuery->get([
            'id',
            'dept_abbr',
            'divisi_abbr',
            'ancak',
            'nomor',
            'status',
            'lat',
            'lon',
            'blok_kode',
            'user_input'
        ]);

        $features = $tphPoints->map(fn($point) => $this->tphToGeoJsonFeature($point))->toArray();

        return [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
    }

    public function getLegendInfo($estateId, $afdelingId, $selectedBlokNama = null)
    {
        $estate = Estate::find($estateId);
        $afdeling = Afdeling::find($afdelingId);

        $query = KoordinatatTph::where('dept', $estate->id)
            ->where('divisi', $afdeling->id);

        // Filter by selected blok if provided
        if ($selectedBlokNama) {
            $query->where('blok_nama', $selectedBlokNama);
        }

        $tph_per_blok = $query->select('blok_kode')
            ->selectRaw('COUNT(*) as total_tph')
            ->selectRaw("SUM(CASE WHEN lon = '-' OR lon = '' OR lon is null OR status != 1 THEN 1 ELSE 0 END) as unverified_tph")
            ->selectRaw("SUM(CASE WHEN status = 1 AND lat != '-' AND lat != '' THEN 1 ELSE 0 END) as verified_tph")
            ->selectRaw("GROUP_CONCAT(CASE WHEN lon = '-' OR lon = '' OR lon is null OR status != 1 THEN nomor ELSE NULL END) as unverified_tph_numbers")
            ->selectRaw("GROUP_CONCAT(CASE WHEN status = 1 AND lat != '-' AND lat != '' THEN nomor ELSE NULL END) as verified_tph_numbers")
            ->groupBy('blok_kode')
            ->get();

        $check_tph_per_blok = !$tph_per_blok->isEmpty();
        $total_blok_name_count = $tph_per_blok->count();
        $total_tph = $tph_per_blok->sum('total_tph');
        $total_tph_verif = $tph_per_blok->sum('verified_tph');
        $total_tph_unverif = $tph_per_blok->sum('unverified_tph');

        $verifiedCount = $tph_per_blok->sum(function ($blok) {
            return $blok->verified_tph > 0 ? 1 : 0;
        });

        $unveridblokcount = $total_blok_name_count - $verifiedCount;
        $progressPercentage = $total_tph > 0 ? round(($total_tph_verif / $total_tph) * 100, 1) : 0;

        // dd([
        //     'progressPercentage' => $progressPercentage,
        //     'total_tph' => $total_tph,
        //     'total_tph_verif' => $total_tph_verif,
        //     'total_tph_unverif' => $total_tph_unverif
        // ]);

        $user_input = KoordinatatTph::where('dept', $estate->id)
            ->where('divisi', $afdeling->id)
            ->where('status', 1)
            ->pluck('user_input')
            ->unique()
            ->values()
            ->toArray();

        return [
            'title' => 'Legend',
            'description' => 'Detail data TPH',
            'total_tph' => $total_tph,
            'total_tph_verif' => $total_tph_verif,
            'total_tph_unverif' => $total_tph_unverif,
            'verifiedblokcount' => $verifiedCount,
            'unveridblokcount' => $unveridblokcount,
            'total_blok_name_count' => $total_blok_name_count,
            'progress_percentage' => $progressPercentage,
            'tph_detail_per_blok' => $tph_per_blok,
            'check_tph_per_blok' => $check_tph_per_blok,
            'user_input' => $user_input,
            'estate_name' => $estate->est,
            'afdeling_name' => $afdeling->nama
        ];
    }

    public function updateTPH($id, $tphNumber, $ancakNumber)
    {
        $tph = KoordinatatTph::find($id);
        if (!$tph) {
            throw new \Exception('TPH not found');
        }

        return $tph->update([
            'nomor' => $tphNumber,
            'ancak' => $ancakNumber,
        ]);
    }

    public function deleteTPH($id)
    {
        $tph = KoordinatatTph::find($id);
        if (!$tph) {
            throw new \Exception('TPH not found');
        }

        return $tph->update(['status' => 0]);
    }

    public function getRegionals()
    {
        return Regional::all();
    }

    public function getWilayahByRegional($regionalId)
    {
        return $regionalId ? Wilayah::where('regional', $regionalId)->get() : [];
    }

    public function getEstateByWilayah($wilayahId)
    {
        return $wilayahId ? Estate::where('wilayah', $wilayahId)
            ->where('status', '=', '1')
            ->get() : [];
    }

    public function getAfdelingByEstate($estateId)
    {
        return $estateId ? Afdeling::where('dept', $estateId)
            ->where('status', '=', '1')
            ->where('id_ppro', '!=', '0')
            ->get() : [];
    }

    public function getBlokByAfdeling($afdelingId)
    {
        return $afdelingId ? Blok::where('divisi', $afdelingId)->get() : [];
    }

    public function editTPH($id)
    {
        $tph = KoordinatatTph::find($id);
        if (!$tph) {
            throw new \Exception('TPH data not found.');
        }

        return [
            'id' => $id,
            'nomor' => $tph->nomor,
            'ancak' => $tph->ancak
        ];
    }

    public function focusOnTPH($estateId, $afdelingId, $blok, $tphNumber)
    {
        $tph = KoordinatatTph::where('dept', $estateId)
            ->where('divisi', $afdelingId)
            ->where('blok_kode', $blok)
            ->where('nomor', $tphNumber)
            ->where('status', 1)
            ->first();

        if (!$tph) {
            throw new \Exception('TPH not found');
        }

        return [
            'lat' => $tph->lat,
            'lon' => $tph->lon,
            'blok' => $blok,
            'ancak' => $tph->ancak,
            'tph' => $tphNumber,
            'estate' => $estateId,
            'afdeling' => $afdelingId,
            'status' => $tph->status,
            'user_input' => $tph->user_input,
            'id' => $tph->id
        ];
    }

    public function getEmptyLegendInfo()
    {
        return [
            'title' => 'Legend',
            'description' => 'Detail data TPH',
            'Total_tph' => 0,
            'verified_tph' => 0,
            'unverified_tph' => 0,
            'progress_percentage' => 0,
            'blok_unverified' => [],
            'blok_tersidak' => [],
            'blok_tersidak_count' => 0,
            'tph_detail_per_blok' => collect([]),
            'total_blok_name_count' => 0,
            'check_tph_per_blok' => true,
            'total_blok_count_unverified' => 0,
            'user_input' => []
        ];
    }
}
