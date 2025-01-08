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

    public function mount()
    {
        $this->title = 'Maps TPH';
        $this->regional = Regional::all();
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

    private function updateTPHCoordinates()
    {
        if (!$this->selectedEstate || !$this->selectedAfdeling) {
            $this->coordinatesTPH = ['type' => 'FeatureCollection', 'features' => []];
            return;
        }

        $est = Estate::find($this->selectedEstate)->est;
        $afd = Afdeling::find($this->selectedAfdeling)->nama;
        $key = $est . '-' . $afd;

        $tphPoints = KoordinatatTph::where('afdeling', $key)->get();

        $features = $tphPoints->map(function ($point) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$point->lon, $point->lat]
                ],
                'properties' => [
                    'id' => $point->id,
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

        $features = $bloks->map(function ($blokGroup, $nama) {
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
                    'afdeling' => $firstBlok->afdeling
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
