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
    public $blok = [];
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
    public $selectedBlok = '';
    public $isProcessing = false;

    public function mount()
    {
        $this->title = 'Maps TPH';
        $this->regional = Regional::all();
        // $this->selectedDate = now()->format('Y-m-d');
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
        $this->isProcessing = true;
        $this->dispatch('show-loader');

        $this->resetSelections('afdeling');
        $this->blok = $this->selectedAfdeling ? Blok::where('afdeling', $this->selectedAfdeling)->get()->unique('nama') : [];

        $this->dispatch('process-afdeling-update');
    }

    public function updatedSelectedBlok()
    {
        $this->resetSelections('blok');
        $this->updateMaps($this->blok);
    }

    public function processAfdelingUpdate()
    {
        $this->updateMaps($this->blok);
        $this->isProcessing = false;
        $this->dispatch('hide-loader');
    }

    public function updatedPlotType()
    {
        $this->updateMaps($this->blok);
    }

    public function updatedSelectedDate()
    {
        if ($this->selectedAfdeling) {
            $this->updateMaps($this->blok);
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
                $this->selectedBlok = '';
                $this->estate = [];
                $this->afdeling = [];
                $this->blok = [];
                break;
            case 'wilayah':
                $this->selectedEstate = '';
                $this->selectedAfdeling = '';
                $this->selectedBlok = '';
                $this->afdeling = [];
                $this->blok = [];
                break;
            case 'estate':
                $this->selectedAfdeling = '';
                $this->selectedBlok = '';
                $this->blok = [];
                break;
            case 'afdeling':
                $this->selectedBlok = '';
                break;
            case 'blok':
                // Only reset plot and TPH data
                break;
        }

        $this->plotMap = [];
        $this->coordinatesTPH = ['type' => 'FeatureCollection', 'features' => []];
    }

    private function updateMaps($blok)
    {
        if ($this->selectedAfdeling) {
            $this->dispatch('show-loader');
            $this->generateMapPlotBlok($blok);
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
        $legendInfo = [
            'title' => 'Legend',
            'description' => 'Detail data TPH',
            'Total_tph' => count($data),
            'blok_tersidak' => $blok,
            // 'tanggal' => Carbon::parse($this->selectedDate)->locale('id')->translatedFormat('l, d F Y'),
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


        // Add this condition if a specific blok is selected
        $tphPoints = KoordinatatTph::where('afdeling', $key);

        // Add this condition if a specific blok is selected
        if ($this->selectedBlok) {
            $blokNama = Blok::find($this->selectedBlok)->nama;
            $tphPoints->where('blok', 'LIKE', '%' . $blokNama . '%');
        }

        // Retrieve the data
        $tphPoints = $tphPoints->get();

        // $tphPoints = $tphPoints->whereDate('datetime', $this->selectedDate)


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

        $query = Blok::where('afdeling', $this->selectedAfdeling);

        // Add filter for specific blok if selected
        if ($this->selectedBlok) {
            $selectedBlokNama = Blok::find($this->selectedBlok)->nama;
            $query->where('nama', $selectedBlokNama);
        }

        $bloks = $query->get()->groupBy('nama');

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


    public function editTPH($id)
    {
        dd($id);
        // Trigger modal dengan data TPH yang akan diedit
        $this->dispatch('show-edit-tph-modal', [
            'tphId' => $id
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
