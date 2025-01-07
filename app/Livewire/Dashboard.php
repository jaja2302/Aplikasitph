<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Regional;
use App\Models\Wilayah;
use App\Models\Estate;
use App\Models\Afdeling;
use App\Models\EstatePlot;
use App\Models\Blok;

class Dashboard extends Component
{
    public $title;
    public $regional;
    public $wilayah = [];
    public $estate = [];
    public $afdeling = [];

    public $selectedRegional = '';
    public $selectedWilayah = '';
    public $selectedEstate = '';
    public $selectedAfdeling = '';
    public $plotMap = [];
    public $plotType = '';

    public function mount()
    {
        $this->title = 'Maps TPH';
        $this->regional = Regional::all();
    }

    public function updatedSelectedRegional($value)
    {
        $this->wilayah = $value ? Wilayah::where('regional', $value)->get() : [];
        $this->selectedWilayah = '';
        $this->estate = [];
        $this->selectedEstate = '';
        $this->afdeling = [];
        $this->selectedAfdeling = '';
    }

    public function updatedSelectedWilayah($value)
    {
        $this->estate = $value ? Estate::where('wil', $value)->get() : [];
        $this->selectedEstate = '';
        $this->afdeling = [];
        $this->selectedAfdeling = '';
    }

    public function updatedSelectedEstate($value)
    {
        $this->afdeling = $value ? Afdeling::where('estate', $value)->get() : [];
        $this->selectedAfdeling = '';
    }


    public function updatedPlotType()
    {
        if ($this->plotType === 'estate') {
            $this->generateMapPlotEstate();
        } else if ($this->plotType === 'blok') {
            $this->generateMapPlotBlok();
        }
    }

    public function updatedSelectedAfdeling()
    {
        // Reset plot type when afdeling changes
        $this->plotType = '';
    }


    public function generateMapPlotEstate()
    {
        $est = Estate::where('id', $this->selectedEstate)->first()->est;
        $plots = EstatePlot::where('est', $est)->get()->toArray();

        // Convert to GeoJSON format
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [
                            // Mengumpulkan semua koordinat menjadi satu array untuk membentuk polygon
                            array_map(function ($plot) {
                                return [$plot['lon'], $plot['lat']];
                            }, $plots)
                        ]
                    ],
                    'properties' => [
                        'estate' => $est
                    ]
                ]
            ]
        ];

        // dd($geojson);
        $this->plotMap = $geojson;
    }

    public function generateMapPlotBlok()
    {
        $est = Estate::where('id', $this->selectedEstate)->first()->est;
        $bloks = Blok::where('afdeling', $this->selectedAfdeling)->get()->toArray();

        // Mengelompokkan data berdasarkan nama blok
        $groupedBloks = collect($bloks)->groupBy('nama')->toArray();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        foreach ($groupedBloks as $nama => $blokGroup) {
            // Mengambil data properti dari item pertama dalam grup
            $firstBlok = $blokGroup[0];

            $feature = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [
                        // Mengumpulkan semua koordinat dalam satu blok
                        array_map(function ($blok) {
                            return [$blok['lon'], $blok['lat']];
                        }, $blokGroup)
                    ]
                ],
                'properties' => [
                    'id' => $firstBlok['id'],
                    'nama' => $nama,
                    'luas' => $firstBlok['luas'],
                    'sph' => $firstBlok['sph'],
                    'bjr' => $firstBlok['bjr'],
                    'afdeling' => $firstBlok['afdeling']
                ]
            ];

            $geojson['features'][] = $feature;
        }
        // dd($geojson);
        $this->plotMap = $geojson;
    }


    public function render()
    {
        return view('livewire.dashboard');
    }
}
