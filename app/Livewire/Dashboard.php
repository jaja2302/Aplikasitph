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
use Illuminate\Support\Facades\Auth;

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
    public $tphData;
    public $editTphId;
    public $editTphNumber;
    public $editAncakNumber;
    public $user;
    public $estateName;
    public $afdelingName;
    public $blokName;
    public function mount()
    {
        $this->title = 'Maps TPH';
        $this->regional = Regional::all();
        $this->user = check_previlege(Auth::user()->user_id);
        // dd($this->user);
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

    /**
     * Get base query for TPH points with valid coordinates
     * 
     * @param string $est Estate code
     * @param string $afdKey Afdeling key
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getBaseTPHQuery($est, $afdKey, $type)
    {
        switch ($type) {
            case 'all':
                return KoordinatatTph::where('dept_abbr', $est)
                    // ->where('status', '=', 1)
                    ->where('divisi_abbr', $afdKey);
            case 'valid':
                return KoordinatatTph::where('dept_abbr', $est)
                    ->where('lat', '!=', null)
                    ->where('lon', '!=', null)
                    ->where('lat', '!=', '-')
                    ->where('lon', '!=', '-')
                    ->where('status', '=', 1)
                    ->where('divisi_abbr', $afdKey);
            case 'unverified':
                return KoordinatatTph::where('dept_abbr', $est)
                    ->where('divisi_abbr', $afdKey)
                    ->where(function ($query) {
                        $query->where('status', '!=', 1)
                            ->orWhereNull('status');
                    });
        }
    }
    /**
     * Convert TPH point to GeoJSON feature
     * 
     * @param KoordinatatTph $point
     * @return array
     */
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
                'tph' => $point->nomor,
                'status' => $point->status,
            ]
        ];
    }

    private function updateTPHCoordinates()
    {
        if (!$this->selectedEstate || !$this->selectedAfdeling) {
            $this->coordinatesTPH = ['type' => 'FeatureCollection', 'features' => []];
            $this->legendInfo = [];
            return;
        }

        $this->estateName = Estate::find($this->selectedEstate)->est;
        $this->afdelingName = Afdeling::find($this->selectedAfdeling)->nama;
        $afdKey = 'AFD' . '-' . $this->afdelingName;

        $tphQuery = $this->getBaseTPHQuery($this->estateName, $afdKey, 'valid');

        if ($this->selectedBlok) {
            $blokNama = Blok::find($this->selectedBlok)->nama;
            $tphQuery->where('blok', 'LIKE', '%' . $blokNama . '%');
        }

        $tphPoints = $tphQuery->get();
        $features = $tphPoints->map(function ($point) {
            return $this->tphToGeoJsonFeature($point);
        })->toArray();

        // Update legend info terpisah dari fokus TPH
        $this->updateLegendInfo($tphPoints);

        $this->coordinatesTPH = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
    }

    private function getBlokTersidak($est, $afdKey)
    {
        return $this->getBaseTPHQuery($est, $afdKey, 'valid')
            ->pluck('blok_kode')
            ->map(function ($blok) {
                return $this->normalizeBlockCode($blok);
            })
            ->unique()
            ->values()
            ->toArray();
    }

    private function generateMapPlotBlok()
    {
        if (!$this->selectedAfdeling) {
            $this->plotMap = [];
            return;
        }

        $query = Blok::where('afdeling', $this->selectedAfdeling);

        if ($this->selectedBlok) {
            $selectedBlokNama = Blok::find($this->selectedBlok)->nama;
            $query->where('nama', $selectedBlokNama);
        }

        $bloks = $query->get()->groupBy('nama');

        $est = Estate::find($this->selectedEstate)->est;
        $afd = Afdeling::find($this->selectedAfdeling)->nama;
        $afdKey = 'AFD' . '-' . $afd;

        $blokTersidak = $this->getBlokTersidak($est, $afdKey);

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
                    // 'status' => $firstBlok->status,
                    'tersidak' => in_array($this->normalizeBlockCode($nama), $blokTersidak)
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
        // Add privilege check before allowing edit
        if (!$this->user) {
            Notification::make()
                ->title('Access Denied')
                ->warning()
                ->body('You do not have permission to edit TPH data.')
                ->send();
            return;
        }

        $tph = KoordinatatTph::find($id);
        if (!$tph) {
            Notification::make()
                ->title('Error')
                ->danger()
                ->body('TPH data not found.')
                ->send();
            return;
        }

        $this->editTphId = $id;
        $this->editTphNumber = $tph->nomor;
        $this->editAncakNumber = $tph->ancak;
        $this->dispatch('open-modal', id: 'modaltph');
    }

    public function updateTPH()
    {
        // Add privilege check before allowing update
        if (!$this->user) {
            Notification::make()
                ->title('Access Denied')
                ->warning()
                ->body('You do not have permission to update TPH data.')
                ->send();
            return;
        }

        // dd($this->user);
        // Validate inputs
        $this->validate([
            'editTphNumber' => 'required|numeric|min:1',
            'editAncakNumber' => 'required|numeric|min:1',
        ], [
            'editTphNumber.required' => 'Nomor TPH harus diisi',
            'editTphNumber.numeric' => 'Nomor TPH harus berupa angka',
            'editTphNumber.min' => 'Nomor TPH minimal 1',
            'editAncakNumber.required' => 'Nomor Ancak harus diisi',
            'editAncakNumber.numeric' => 'Nomor Ancak harus berupa angka',
            'editAncakNumber.min' => 'Nomor Ancak minimal 1',
        ]);


        $tph = KoordinatatTph::find($this->editTphId);

        // dd($tph);
        if (!$tph) {
            Notification::make()
                ->title('Error')
                ->danger()
                ->body('TPH data not found.')
                ->send();
            return;
        }
        // dd($this->editTphNumber, $this->editAncakNumber, $tph);
        try {
            $tph->update([
                'nomor' => $this->editTphNumber,
                'ancak' => $this->editAncakNumber,
            ]);

            $this->dispatch('close-modal', id: 'modaltph');
            $this->updateTPHCoordinates(); // Refresh data TPH

            Notification::make()
                ->title('Berhasil')
                ->success()
                ->body('Data TPH berhasil diperbarui.')
                ->send();
        } catch (\Exception $e) {
            // dd($e);
            Notification::make()
                ->title('Error')
                ->danger()
                ->body('Gagal memperbarui data TPH.')
                ->send();
        }
    }

    public function confirmDeleteTPH()
    {
        // Validate that we have an ID to delete
        if (!$this->editTphId) {
            Notification::make()
                ->title('Error')
                ->danger()
                ->body('No TPH selected for deletion.')
                ->send();
            return;
        }

        $this->dispatch('open-modal', id: 'confirmdelete');
    }

    public function deleteTPH()
    {
        // Check user privileges
        if (!$this->user) {
            Notification::make()
                ->title('Access Denied')
                ->warning()
                ->body('You do not have permission to delete TPH data.')
                ->send();
            return;
        }

        try {
            $tph = KoordinatatTph::find($this->editTphId);
            if (!$tph) {
                Notification::make()
                    ->title('Error')
                    ->danger()
                    ->body('TPH data not found.')
                    ->send();
                return;
            }

            // Instead of deleting, update status to 0
            $tph->update([
                'status' => 0,
            ]);

            // Close the modal after successful update
            $this->dispatch('close-modal', id: 'confirmdelete');
            $this->dispatch('close-modal', id: 'modaltph');

            // Refresh TPH data
            $this->updateTPHCoordinates();

            Notification::make()
                ->title('Berhasil')
                ->success()
                ->body('Status TPH berhasil diubah menjadi tidak aktif.')
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->danger()
                ->body('Gagal mengubah status TPH.')
                ->send();
        }
    }

    /**
     * Normalize block codes for comparison by removing common prefixes and suffixes
     * 
     * @param string $blockCode The block code to normalize
     * @return string Normalized block code
     */
    private function normalizeBlockCode($blockCode)
    {
        if (!$blockCode) return '';

        // Convert to uppercase first
        $blockCode = strtoupper($blockCode);

        // Remove any single letter suffix (A, B, etc) and leading 'C'
        return preg_replace('/[A-Z]$/', '', ltrim($blockCode, 'C'));
    }

    public function focusOnTPH($blok, $tphNumber)
    {
        // Find the TPH coordinates
        if (!$this->selectedEstate || !$this->selectedAfdeling) {
            return;
        }

        $est = Estate::find($this->selectedEstate)->est;
        $afd = Afdeling::find($this->selectedAfdeling)->nama;
        $afdKey = 'AFD' . '-' . $afd;

        $tph = KoordinatatTph::where('dept_abbr', $est)
            ->where('divisi_abbr', $afdKey)
            ->where('blok_kode', $blok)
            ->where('nomor', $tphNumber)
            ->where('status', 1)
            ->first();

        if ($tph) {
            // Only dispatch the focus event without updating any other data
            $this->dispatch('focus-tph', coordinates: [
                'lat' => $tph->lat,
                'lon' => $tph->lon,
                'blok' => $blok,
                'tph' => $tphNumber,
                'estate' => $est,
                'afdeling' => $afd,
                'status' => $tph->status
            ]);

            // Clear the legend info temporarily
            $this->legendInfo = [
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
                'total_blok_count_unverified' => 0,
                'user_input' => []
            ];
        }
    }

    // Tambahkan method baru untuk memisahkan logika update legend
    private function updateLegendInfo($tphPoints)
    {
        if (!$this->selectedEstate || !$this->selectedAfdeling) {
            $this->legendInfo = [];
            return;
        }

        $est = Estate::find($this->selectedEstate)->est;
        $afd = Afdeling::find($this->selectedAfdeling)->nama;
        $afdKey = 'AFD' . '-' . $afd;

        // titik tph valid
        $blok_tersidak = $tphPoints->pluck('blok_kode')->unique()->values()->toArray();
        $estate = $tphPoints->pluck('dept_abbr')->unique()->values()->toArray();
        $afdeling = $tphPoints->pluck('divisi_abbr')->unique()->values()->toArray();
        $verifiedCount = $tphPoints->count();

        // dd($blok_tersidak);
        // Get unverified data
        $data_unverified = $this->getBaseTPHQuery($est, $afdKey, 'unverified');
        $unverifiedCount = $data_unverified->count();
        $unveridblok = $data_unverified->pluck('blok_kode')->unique()->values()->toArray();

        // get detail tph per blok
        $tph_per_blok = $this->getBaseTPHQuery($est, $afdKey, 'all')
            ->select('blok_kode')
            ->selectRaw('COUNT(*) as total_tph')
            ->selectRaw("SUM(CASE WHEN lon = '-' THEN 1 ELSE 0 END) as unverified_tph")
            ->selectRaw("SUM(CASE WHEN lon != '-' THEN 1 ELSE 0 END) as verified_tph")
            ->selectRaw("GROUP_CONCAT(CASE WHEN lon = '-' THEN nomor ELSE NULL END) as unverified_tph_numbers")
            ->selectRaw("GROUP_CONCAT(CASE WHEN status = 1 THEN nomor ELSE NULL END) as verified_tph_numbers")
            ->groupBy('blok_kode')
            ->get();

        // dd($tph_per_blok);
        $total_blok_name_count = $tph_per_blok->count();
        $total_tph = $tph_per_blok->sum('total_tph');

        $progressPercentage = $total_tph > 0
            ? round(($verifiedCount / $total_tph) * 100, 1)
            : 0;

        $this->legendInfo = [
            'title' => 'Legend',
            'description' => 'Detail data TPH',
            'Total_tph' => $total_tph,
            'verified_tph' => $verifiedCount,
            'unverified_tph' => $unverifiedCount,
            'progress_percentage' => $progressPercentage,
            'blok_unverified' => $unveridblok,
            'blok_tersidak' => $blok_tersidak,
            'blok_tersidak_count' => count($blok_tersidak),
            'tph_detail_per_blok' => $tph_per_blok,
            'total_blok_name_count' => $total_blok_name_count,
            'total_blok_count_unverified' => $total_blok_name_count - count($blok_tersidak),
            'user_input' => $tphPoints->pluck('user_input')->unique()->values()->toArray()
        ];
    }

    public function resetMapView()
    {
        if (!$this->selectedEstate || !$this->selectedAfdeling) {
            return;
        }

        $est = Estate::find($this->selectedEstate)->est;
        $afd = Afdeling::find($this->selectedAfdeling)->nama;
        $afdKey = 'AFD' . '-' . $afd;

        // Get all TPH points for the current afdeling
        $tphPoints = $this->getBaseTPHQuery($est, $afdKey, 'valid')->get();

        // Update the TPH coordinates
        $features = $tphPoints->map(function ($point) {
            return $this->tphToGeoJsonFeature($point);
        })->toArray();

        $this->coordinatesTPH = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];

        // Restore the full legend info
        $this->updateLegendInfo($tphPoints);

        // Reset the map view
        $this->dispatch('reset-map-view', [
            'plotMap' => $this->plotMap,
            'coordinatesTPH' => $this->coordinatesTPH
        ]);

        // Clear any selected blok
        $this->selectedBlok = '';
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
