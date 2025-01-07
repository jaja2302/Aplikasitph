<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Regional;
use App\Models\Wilayah;
use App\Models\Estate;
use App\Models\Afdeling;

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

    public function render()
    {
        return view('livewire.dashboard');
    }
}
