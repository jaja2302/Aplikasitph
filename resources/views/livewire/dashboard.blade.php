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
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Basic Info Section -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">{{ $legendInfo['title'] }}</h3>
                <p class="text-gray-600">{{ $legendInfo['description'] }}</p>

                <div class="space-y-2">
                    <p class="font-medium">Total TPH: {{ $legendInfo['Total_tph'] }}</p>
                    <div class="flex items-center space-x-2">
                        <span class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-green-600 mr-2"></span>
                            Terverifikasi: {{ $legendInfo['verified_tph'] }}
                        </span>
                        <span class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-red-600 mr-2"></span>
                            Belum: {{ $legendInfo['unverified_tph'] }}
                        </span>
                    </div>
                    <div class="relative pt-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold inline-block text-green-600">
                                    Progress: {{ $legendInfo['progress_percentage'] }}%
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                            <div style="width: {{ $legendInfo['progress_percentage'] }}%"
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-600">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Petugas & Blok Tersidak Section -->
            <div class="space-y-4">
                @if(isset($legendInfo['user_input']) && count($legendInfo['user_input']) > 0)
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Petugas:</h4>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($legendInfo['user_input'] as $user)
                        <li class="text-gray-600">{{ $user }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(isset($legendInfo['blok_tersidak']) && count($legendInfo['blok_tersidak']) > 0)
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Blok Tersidak:</h4>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($legendInfo['blok_tersidak'] as $blok)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $blok }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Blok Belum Tersidak Section -->
            <div>
                @if(isset($legendInfo['blok_unverified']) && count($legendInfo['blok_unverified']) > 0)
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Blok Belum Tersidak:</h4>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($legendInfo['blok_unverified'] as $blok)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ $blok }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
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
                            fillColor: isTersidak ? '#4CAF50' : '#FF5252', // Hijau jika tersidak, Merah jika belum
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
    });
</script>
@endpush