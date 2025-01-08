<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Regional;
use App\Models\Wilayah;
use App\Models\Estate;
use App\Models\Afdeling;
use App\Models\EstatePlot;
use App\Models\Blok;
use App\Models\KoordinatatTph;
use Filament\Notifications\Notification;
use Carbon\Carbon;

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
    public $coordinatesTPH = ['type' => 'FeatureCollection', 'features' => []];
    public $isLoading = false;
    public $legendInfo = [];
    public $blokTersidak = [];
    public $selectedDate;

    public function mount()
    {
        $this->title = 'Maps TPH';
        $this->regional = Regional::all();
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function updatedSelectedRegional()
    {
        $this->resetSelections('regional');
        $this->wilayah = $this->selectedRegional ? Wilayah::where('regional', $this->selectedRegional)->get() : [];
    }

    public function updatedSelectedWilayah()
    {
        $this->resetSelections('wilayah');
        $this->estate = $this->selectedWilayah ? Estate::where('wil', $this->selectedWilayah)->get() : [];
    }

    public function updatedSelectedEstate()
    {
        $this->resetSelections('estate');
        $this->afdeling = $this->selectedEstate ? Afdeling::where('estate', $this->selectedEstate)->get() : [];
    }

    public function updatedSelectedAfdeling()
    {
        $this->resetSelections('afdeling');
        $this->updateMaps();
    }

    public function processAfdelingUpdate()
    {
        $this->dispatch('show-loader');
        $this->isLoading = true;
        $this->resetSelections('afdeling');
        $this->updateMaps();
        $this->dispatch('hide-loader');
    }

    public function updatedPlotType()
    {
        $this->updateMaps();
    }

    public function updatedSelectedDate()
    {
        if ($this->selectedAfdeling) {
            $this->updateMaps();
        }
    }

    private function resetSelections($level)
    {
        // Reset plotType untuk semua level
        // $this->plotType = '';

        switch ($level) {
            case 'regional':
                $this->selectedWilayah = '';
                $this->selectedEstate = '';
                $this->selectedAfdeling = '';
                $this->estate = [];
                $this->afdeling = [];
                break;
            case 'wilayah':
                $this->selectedEstate = '';
                $this->selectedAfdeling = '';
                $this->afdeling = [];
                break;
            case 'estate':
                $this->selectedAfdeling = '';
                break;
            case 'afdeling':
                // Hanya reset plot dan TPH data
                break;
        }

        $this->plotMap = [];
        $this->coordinatesTPH = ['type' => 'FeatureCollection', 'features' => []];
    }

    private function updateMaps()
    {
        if ($this->selectedAfdeling) {
            $this->dispatch('show-loader');
            $this->generateMapPlotBlok();
            $this->updateTPHCoordinates();

            $this->dispatch('hide-loader');
        }
    }

    private function generateLegendInfo($data)
    {
        $blok = $data->pluck('blok')->map(function ($blok) {
            $parts = explode('-', $blok);
            return end($parts); // Get last item after explode
        })->unique()->values()->toArray();

        $this->blokTersidak = $blok;

        $legendInfo = [
            'title' => 'Legend',
            'description' => 'Detail data TPH',
            'Total_tph' => count($data),
            'blok_tersidak' => $blok,
            'tanggal' => Carbon::parse($this->selectedDate)->locale('id')->translatedFormat('l, d F Y'),
            'user_input' => $data->pluck('user_input')->unique()->values()->toArray()
        ];

        $this->legendInfo = $legendInfo;
    }

    private function updateTPHCoordinates()
    {
        if (!$this->selectedEstate || !$this->selectedAfdeling) {
            $this->coordinatesTPH = ['type' => 'FeatureCollection', 'features' => []];
            return;
        }

        $est = Estate::find($this->selectedEstate)->est;
        $afd = Afdeling::find($this->selectedAfdeling)->nama;
        $key = $est . '-' . $afd;

        $tphPoints = KoordinatatTph::where('afdeling', $key)
            ->whereDate('datetime', $this->selectedDate)
            ->get();

        $this->generateLegendInfo($tphPoints);

        $features = $tphPoints->map(function ($point) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$point->lon, $point->lat]
                ],
                'properties' => [
                    'id' => $point->id,
                    'tanggal' => Carbon::parse($point->datetime)->locale('id')->translatedFormat('l, d F Y \P\u\k\u\l H:i'),
                    'datetime' => $point->datetime,
                    'user_input' => $point->user_input,
                    'estate' => $point->estate,
                    'afdeling' => $point->afdeling,
                    'blok' => $point->blok,
                    'ancak' => $point->ancak,
                    'tph' => $point->tph
                ]
            ];
        })->toArray();

        $this->coordinatesTPH = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
    }

    private function generateMapPlotBlok()
    {
        if (!$this->selectedAfdeling) {
            $this->plotMap = [];
            return;
        }

        $bloks = Blok::where('afdeling', $this->selectedAfdeling)
            ->get()
            ->groupBy('nama');
        $est = Estate::find($this->selectedEstate)->est;
        $afd = Afdeling::find($this->selectedAfdeling)->nama;
        $key = $est . '-' . $afd;


        $blokTersidak = KoordinatatTph::where('afdeling', $key)
            ->pluck('blok')
            ->map(function ($blok) {
                $parts = explode('-', $blok);
                return end($parts);
            })
            ->unique()
            ->values()
            ->toArray();

        // dd($blokTersidak);       

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
                    'luas' => $firstBlok->luas,
                    'sph' => $firstBlok->sph,
                    'bjr' => $firstBlok->bjr,
                    'afdeling' => $firstBlok->afdeling,
                    'tersidak' => in_array($nama, $blokTersidak)
                ]
            ];
        })->values()->toArray();

        $this->plotMap = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
