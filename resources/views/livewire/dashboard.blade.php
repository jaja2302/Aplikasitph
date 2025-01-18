<div class="space-y-6 p-4 bg-gray-50 min-h-screen">
    <x-filament::modal id="modaltph" width="md">
        <x-slot name="heading">
            Edit TPH
        </x-slot>

        <div class="space-y-4 py-3">
            <div class="grid grid-cols-2 gap-4">
                <!-- TPH Number Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor TPH
                    </label>
                    <input
                        wire:model="editTphNumber"
                        type="number"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="Masukkan nomor TPH" />
                </div>

                <!-- Ancak Number Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nomor Ancak
                    </label>
                    <input
                        wire:model="editAncakNumber"
                        type="number"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="Masukkan nomor ancak" />
                </div>
            </div>
        </div>

        <x-slot name="footerActions">
            <x-filament::button wire:click="confirmDeleteTPH" wire:loading.attr="disabled" class="bg-red-500 hover:bg-red-600">
                <span wire:loading.remove>Hapus</span>
                <span wire:loading>Hapus...</span>
            </x-filament::button>
            <x-filament::button wire:click="updateTPH" wire:loading.attr="disabled">
                <span wire:loading.remove>Simpan Perubahan</span>
                <span wire:loading>Menyimpan...</span>
            </x-filament::button>

        </x-slot>
    </x-filament::modal>

    <x-filament::modal id="confirmdelete" width="md">
        <x-slot name="heading">
            Konfirmasi Hapus
        </x-slot>
        <div class="space-y-4 py-3">
            <p>Apakah Anda yakin ingin menghapus data TPH ini?</p>
        </div>
        <x-slot name="footerActions">
            <x-filament::button
                wire:click="$dispatch('close-modal', { id: 'confirmdelete' })"
                class="mr-2">
                Batal
            </x-filament::button>
            <x-filament::button
                wire:click="deleteTPH"
                wire:loading.attr="disabled"
                class="bg-red-500 hover:bg-red-600">
                <span wire:loading.remove>Hapus</span>
                <span wire:loading>Menghapus...</span>
            </x-filament::button>
        </x-slot>
    </x-filament::modal>

    <!-- Header with Breadcrumb -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-center space-x-2 text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
            </svg>
            <span class="text-sm">Dashboard</span>
            <span class="text-sm"></span>
            <span class="text-sm font-medium text-green-600">{{ $title }}</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $title }}</h1>
    </div>

    <!-- Filter Panel -->
    <div x-data="{ open: false }" class="bg-white shadow-sm rounded-lg overflow-hidden">
        <!-- Toggle Button -->
        <button @click="open = !open"
            class="w-full flex items-center justify-between p-4 hover:bg-gray-50 transition-colors duration-200">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <h2 class="text-lg font-medium text-gray-700">Filter Data</h2>
            </div>
            <svg :class="open ? 'rotate-180 transform' : ''"
                class="w-5 h-5 text-gray-500 transition-transform duration-200"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Filter Content -->
        <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="p-6 border-t border-gray-100">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Regional Select -->
                <div class="space-y-2">
                    <label for="regional" class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                        </svg>
                        Regional
                    </label>
                    <select id="regional"
                        wire:model.live="selectedRegional"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                        @if($isProcessing) disabled @endif>
                        <option value="">Pilih Regional</option>
                        @foreach($regional as $reg)
                        <option value="{{ $reg->id }}">{{ $reg->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Wilayah Select -->
                <div class="space-y-2">
                    <label for="wilayah" class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Wilayah
                    </label>
                    <select id="wilayah"
                        wire:model.live="selectedWilayah"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                        @if(!$wilayah || $isProcessing) disabled @endif>
                        <option value="">Pilih Wilayah</option>
                        @foreach($wilayah as $wil)
                        <option value="{{ $wil->id }}">{{ $wil->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Estate Select -->
                <div class="space-y-2">
                    <label for="estate" class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Estate
                    </label>
                    <select id="estate"
                        wire:model.live="selectedEstate"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                        @if(!$estate || $isProcessing) disabled @endif>
                        <option value="">Pilih Estate</option>
                        @foreach($estate as $est)
                        <option value="{{ $est->id }}">{{ $est->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Afdeling Select -->
                <div class="space-y-2">
                    <label for="afdeling" class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Afdeling
                    </label>
                    <select id="afdeling"
                        wire:model.live="selectedAfdeling"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                        @if(!$afdeling || $isProcessing) disabled @endif>
                        <option value="">Pilih Afdeling</option>
                        @foreach($afdeling as $afd)
                        <option value="{{ $afd->id }}">{{ $afd->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Blok Select -->
                <div class="space-y-2">
                    <label for="blok" class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                        Blok
                    </label>
                    <select id="blok"
                        wire:model.live="selectedBlok"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                        @if(!$blok || $isProcessing) disabled @endif>
                        <option value="">Pilih Blok</option>
                        @foreach($blok as $blk)
                        <option value="{{ $blk->id }}">{{ $blk->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Container with Loading State -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden" style="position: relative; z-index: 0;">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Peta Lokasi</h3>
        </div>

        <div class="relative">
            <div wire:ignore class="h-[600px]" id="map"></div>

            <!-- Loading Overlay -->
            <div wire:loading class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600"></div>
                    <span class="mt-2 text-sm text-gray-600">Memuat data...</span>
                </div>
            </div>


        </div>
    </div>
    @if($legendInfo)
    <!-- Legend Card -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="space-y-6">
            <!-- Basic Info Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Detail Data TPH</h3>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-600">Total Blok</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $legendInfo['total_blok_name_count'] }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-600">Total TPH</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $legendInfo['Total_tph'] }}</div>
                        </div>
                    </div>
                </div>

                <!-- Petugas Section -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Petugas</h4>
                    <div class="space-y-2">
                        @foreach($legendInfo['user_input'] as $user)
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            <span class="text-sm text-gray-600">{{ $user }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Full Width Progress Section -->
            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Status TPH</span>
                    <span class="text-sm font-medium text-gray-900">{{ $legendInfo['progress_percentage'] }}%</span>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        <span class="text-sm">Terverifikasi: {{ $legendInfo['verified_tph'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="text-sm">Belum: {{ $legendInfo['unverified_tph'] }}</span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="relative pt-1">
                    <div class="h-2 rounded-full bg-gray-200">
                        <div class="h-2 rounded-full bg-green-500 transition-all duration-300"
                            style="width: {{ $legendInfo['progress_percentage'] }}%">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blok Status Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Blok Tersidak -->
                <div class="bg-green-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-green-900 mb-3">Blok Tersidak</h4>
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($legendInfo['blok_tersidak'] as $blok)
                        @php
                        $tphDetail = collect($legendInfo['tph_detail_per_blok'])
                        ->where('blok_kode', $blok)
                        ->first();
                        $totalTph = $tphDetail->total_tph ?? 0;
                        $verifiedTph = $tphDetail->verified_tph ?? 0;
                        @endphp
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $blok }} ({{ $verifiedTph }}/{{ $totalTph }})
                            </span>
                            @if($tphDetail && $tphDetail->verified_tph_numbers)
                            <div class="text-xs text-gray-600 break-words">
                                <span class="font-medium">TPH:</span>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(explode(',', $tphDetail->verified_tph_numbers) as $tphNumber)
                                    <span class="px-1.5 py-0.5 bg-green-50 rounded">{{ $tphNumber }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Blok Belum Tersidak -->
                @if(isset($legendInfo['blok_unverified']) && count($legendInfo['blok_unverified']) > 0)
                <div class="bg-red-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-red-900 mb-3">Blok Belum Tersidak</h4>
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($legendInfo['blok_unverified'] as $blok)
                        @php
                        $tphDetail = collect($legendInfo['tph_detail_per_blok'])
                        ->where('blok_kode', $blok)
                        ->first();
                        $totalTph = $tphDetail->total_tph ?? 0;
                        $unverifiedTph = $tphDetail->unverified_tph ?? 0;
                        @endphp
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $blok }} ({{ $unverifiedTph }}/{{ $totalTph }})
                            </span>
                            @if($tphDetail && $tphDetail->unverified_tph_numbers)
                            <div class="text-xs text-gray-600 break-words">
                                <span class="font-medium">TPH:</span>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(explode(',', $tphDetail->unverified_tph_numbers) as $tphNumber)
                                    <span class="px-1.5 py-0.5 bg-red-50 rounded">{{ $tphNumber }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Tambahkan control search setelah opacity control -->
    <div class="leaflet-control search-control" style="position: absolute; top: 10px; right: 10px; z-index: 1000;">
        <div class="bg-white p-2 rounded-lg shadow-lg">
            <div class="flex items-center space-x-2">
                <input type="text"
                    id="searchBlok"
                    placeholder="Cari Blok..."
                    class="px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                    style="min-width: 200px;">
                <button id="searchButton"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition-colors">
                    Cari
                </button>
            </div>
            <div id="searchResults" class="mt-2 max-h-48 overflow-y-auto hidden">
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script type="module">
    document.addEventListener('livewire:initialized', () => {
        const map = L.map('map').setView([-2.2653013566283, 111.65335780362], 13);
        let tphLayer = null;
        let plotLayer = null;
        let labelMarkers = [];
        let currentFillOpacity = 0.7;

        // Custom Control untuk Opacity
        L.Control.OpacitySlider = L.Control.extend({
            onAdd: function(map) {
                const container = L.DomUtil.create('div', 'leaflet-control opacity-control');
                container.style.backgroundColor = 'white';
                container.style.padding = '10px';
                container.style.borderRadius = '5px';
                container.style.boxShadow = '0 1px 5px rgba(0,0,0,0.4)';

                const label = L.DomUtil.create('div', '', container);
                label.innerHTML = 'Opacity: <span id="opacity-value">70%</span>';

                const slider = L.DomUtil.create('input', '', container);
                slider.type = 'range';
                slider.min = '0';
                slider.max = '100';
                slider.value = '70';
                slider.style.width = '150px';
                slider.style.marginTop = '5px';

                L.DomEvent.on(slider, 'input', function(e) {
                    const value = e.target.value;
                    document.getElementById('opacity-value').innerHTML = value + '%';
                    currentFillOpacity = value / 100;

                    if (plotLayer) {
                        plotLayer.eachLayer(function(layer) {
                            layer.setStyle({
                                fillOpacity: currentFillOpacity
                            });
                        });
                    }
                });

                // Prevent map drag when using the slider
                L.DomEvent.disableClickPropagation(container);

                return container;
            }
        });

        // Add the custom control
        new L.Control.OpacitySlider({
            position: 'topright'
        }).addTo(map);

        // Base layers
        const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        });

        // Google Satellite layer
        const satelliteLayer = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '© Google'
        });

        // Layer control
        const baseLayers = {
            "OpenStreetMap": osmLayer,
            "Satellite": satelliteLayer
        };

        // Add default layer
        satelliteLayer.addTo(map);

        // Add layer control to map
        L.control.layers(baseLayers, null, {
            position: 'topright'
        }).addTo(map);

        function updateTPHMarkers(value) {
            if (tphLayer) {
                map.removeLayer(tphLayer);
                tphLayer = null;
            }

            if (value && Array.isArray(value.features) && value.features.length > 0) {
                tphLayer = L.featureGroup();

                L.geoJSON(value, {
                    filter: function(feature) {
                        // Skip features with null/invalid coordinates
                        const coords = feature.geometry?.coordinates;
                        return coords &&
                            Array.isArray(coords) &&
                            coords.length === 2 &&
                            coords[0] !== null &&
                            coords[1] !== null;
                    },
                    pointToLayer: function(feature, latlng) {
                        const markerColor = feature.properties.status === 1 ? "#4CAF50" : "#FF0000";
                        return L.circleMarker(latlng, {
                            radius: 8,
                            fillColor: markerColor,
                            color: "#000",
                            weight: 1,
                            opacity: 1,
                            fillOpacity: 0.8,
                            draggable: true
                        });
                    },
                    onEachFeature: function(feature, layer) {
                        // Get user privileges from Livewire component
                        const hasEditPrivilege = @this.user;

                        let statusText = feature.properties.status === 1 ?
                            '<span class="text-green-600">Terverifikasi</span>' :
                            '<span class="text-red-600">Belum Terverifikasi</span>';

                        let popupContent = `
                            <strong>TPH Info</strong><br>
                            Blok: ${feature.properties.blok}<br>
                            Ancak: ${feature.properties.ancak}<br>
                            TPH: ${feature.properties.tph}<br>
                            Estate: ${feature.properties.estate}<br>
                            Afdeling: ${feature.properties.afdeling}<br>
                            Status: ${statusText}
                        `;

                        // Only add edit button if user has privileges
                        if (hasEditPrivilege) {
                            popupContent += `
                                <button wire:click="editTPH(${feature.properties.id})"
                                    style="background: #4CAF50; color: white; padding: 4px 8px; 
                                    border: none; border-radius: 4px; cursor: pointer; margin-top: 8px;">
                                    Edit TPH
                                </button>
                            `;
                        }

                        layer.bindPopup(popupContent);
                    }
                }).addTo(tphLayer);

                map.addLayer(tphLayer);
            }
        }

        function getColorByBlok(blokName) {
            // Menggunakan nama blok sebagai seed untuk menghasilkan warna yang konsisten
            let hash = 0;
            for (let i = 0; i < blokName.length; i++) {
                hash = blokName.charCodeAt(i) + ((hash << 5) - hash);
            }

            // Mengkonversi hash menjadi warna HSL
            // Menggunakan HSL untuk mendapatkan warna yang lebih cerah dan mudah dibedakan
            const hue = hash % 360;
            return `hsl(${hue}, 70%, 60%)`; // Saturation 70% dan Lightness 60% untuk warna yang lebih cerah
        }

        function updatePlotLayer(value) {
            if (plotLayer) {
                map.removeLayer(plotLayer);
                plotLayer = null;
            }

            labelMarkers.forEach(marker => map.removeLayer(marker));
            labelMarkers = [];

            if (value && value.features) {
                plotLayer = L.geoJSON(value, {
                    style: function(feature) {
                        const isTersidak = feature.properties.tersidak;
                        return {
                            fillColor: isTersidak ? '#87CEEB' : '#FF5252', // Hijau jika tersidak, Merah jika belum
                            weight: 2,
                            opacity: 1,
                            color: 'white',
                            dashArray: '3',
                            fillOpacity: currentFillOpacity
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        let popupContent = '';
                        if (feature.properties.estate) {
                            popupContent = `
                                <strong>ID: ${feature.properties.id}</strong><br>
                                Estate: ${feature.properties.estate}
                            `;
                        } else {
                            popupContent = `
                                <strong>Blok: ${feature.properties.nama}</strong><br>
                                Luas: ${feature.properties.luas || 'N/A'}<br>
                                SPH: ${feature.properties.sph || 'N/A'}<br>
                                BJR: ${feature.properties.bjr || 'N/A'}<br>
                                Afdeling: ${feature.properties.afdeling}
                            `;
                        }
                        layer.bindPopup(popupContent);

                        layer.on({
                            mouseover: function(e) {
                                var layer = e.target;
                                layer.setStyle({
                                    weight: 5,
                                    color: '#666',
                                    dashArray: '',
                                    fillOpacity: Math.min(currentFillOpacity + 0.2, 1.0)
                                });
                            },
                            mouseout: function(e) {
                                var layer = e.target;
                                layer.setStyle({
                                    weight: 2,
                                    color: 'white',
                                    dashArray: '3',
                                    fillOpacity: currentFillOpacity
                                });
                            }
                        });

                        // Add center label
                        if (feature.geometry.type === 'Polygon') {
                            const center = layer.getBounds().getCenter();
                            const label = L.divIcon({
                                className: 'blok-label',
                                html: `<div style="
                                    background-color: rgba(255, 255, 255, 0.8);
                                    padding: 5px;
                                    border-radius: 3px;
                                    font-weight: bold;
                                    font-size: 12px;
                                    border: 1px solid #666;
                                    white-space: nowrap;
                                ">${feature.properties.nama}</div>`,
                                iconSize: [50, 20],
                                iconAnchor: [25, 10]
                            });
                            const labelMarker = L.marker(center, {
                                icon: label
                            }).addTo(map);
                            labelMarkers.push(labelMarker);
                        }
                    }
                }).addTo(map);

                const bounds = L.geoJSON(value).getBounds();
                map.fitBounds(bounds);
            }
        }

        @this.watch('plotMap', value => {
            updatePlotLayer(value);
            if (@this.coordinatesTPH) {
                updateTPHMarkers(@this.coordinatesTPH);
            }
        });

        @this.watch('coordinatesTPH', value => {
            updateTPHMarkers(value);
        });

        // Add Livewire event listeners for loader
        Livewire.on('show-loader', () => {
            window.showLoader();
        });

        Livewire.on('hide-loader', () => {
            window.hideLoader();
        });

        // Add listener for process-afdeling-update
        Livewire.on('process-afdeling-update', () => {
            @this.processAfdelingUpdate();
        });

        window.updateTPHDetails = function(id) {
            const ancak = document.getElementById(`ancak-${id}`).value;
            const tph = document.getElementById(`tph-${id}`).value;

            if (confirm('Apakah Anda yakin ingin memperbarui data TPH ini?')) {
                @this.updateTPHDetails(id, ancak, tph);
            }
        }

        // Tambahkan fungsi search
        function initializeSearch() {
            const searchInput = document.getElementById('searchBlok');
            const searchButton = document.getElementById('searchButton');
            const searchResults = document.getElementById('searchResults');
            let markers = [];

            function clearMarkers() {
                markers.forEach(marker => map.removeLayer(marker));
                markers = [];
            }

            function highlightTPH(coordinates, blok, tphNumber) {
                clearMarkers();

                // Buat marker khusus untuk hasil pencarian
                const marker = L.circleMarker([coordinates[1], coordinates[0]], {
                    radius: 12,
                    fillColor: '#FFD700',
                    color: '#000',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.8
                });

                marker.bindPopup(`<b>Blok: ${blok}</b><br>TPH: ${tphNumber}`).openPopup();
                marker.addTo(map);
                markers.push(marker);

                // Zoom ke lokasi TPH
                map.setView([coordinates[1], coordinates[0]], 18);
            }

            function performSearch() {
                const searchTerm = searchInput.value.trim();
                if (!searchTerm) return;

                @this.searchTPHByBlok(searchTerm).then(results => {
                    searchResults.innerHTML = '';
                    searchResults.classList.remove('hidden');

                    if (results.length === 0) {
                        searchResults.innerHTML = `
                            <div class="p-2 text-sm text-gray-600">
                                Tidak ada hasil ditemukan
                            </div>
                        `;
                        return;
                    }

                    results.forEach(result => {
                        const resultDiv = document.createElement('div');
                        resultDiv.className = 'p-2 hover:bg-gray-100 cursor-pointer text-sm';
                        resultDiv.innerHTML = `Blok ${result.blok} - TPH ${result.tph}`;

                        resultDiv.addEventListener('click', () => {
                            highlightTPH(result.coordinates, result.blok, result.tph);
                            searchResults.classList.add('hidden');
                        });

                        searchResults.appendChild(resultDiv);
                    });
                });
            }

            searchButton.addEventListener('click', performSearch);
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') performSearch();
            });

            // Sembunyikan hasil pencarian ketika klik di luar
            document.addEventListener('click', (e) => {
                if (!searchResults.contains(e.target) && e.target !== searchInput) {
                    searchResults.classList.add('hidden');
                }
            });
        }

        // Panggil fungsi initializeSearch setelah map diinisialisasi
        document.addEventListener('livewire:initialized', () => {
            // ... kode map initialization yang sudah ada ...

            initializeSearch();
        });
    });
</script>

<style>
    .search-control {
        background: transparent;
        border: none;
    }

    #searchResults {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-top: 0.5rem;
    }

    #searchResults div {
        transition: background-color 0.2s;
    }

    #searchResults div:hover {
        background-color: #f3f4f6;
    }
</style>
@endpush