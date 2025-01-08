<div class="h-screen bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg p-4">
        <div class="container mx-auto">
            <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
        </div>
    </nav>

    <!-- Dropdown Container -->
    <div class="container mx-auto p-4">
        <div class="grid grid-cols-2 gap-4">
            <!-- select regional -->
            <div>
                <select wire:model.live="selectedRegional" class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="" class="text-gray-500">-- Pilih Regional --</option>
                    @foreach ($regional as $item)
                    <option value="{{ $item->id }}" class="py-2">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- select wilayah based regional -->
            <div>
                <select wire:model.live="selectedWilayah" class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-100 disabled:text-gray-500" @if(!$wilayah) disabled @endif>
                    <option value="" class="text-gray-500">-- Pilih Wilayah --</option>
                    @foreach ($wilayah ?? [] as $item)
                    <option value="{{ $item->id }}" class="py-2">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- select estate based wilayah -->
            <div>
                <select wire:model.live="selectedEstate" class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-100 disabled:text-gray-500" @if(!$estate) disabled @endif>
                    <option value="" class="text-gray-500">-- Pilih Estate --</option>
                    @foreach ($estate ?? [] as $item)
                    <option value="{{ $item->id }}" class="py-2">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- select afdeling based estate -->
            <div>
                <select wire:model.live="selectedAfdeling" class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-100 disabled:text-gray-500" @if(!$afdeling) disabled @endif>
                    <option value="" class="text-gray-500">-- Pilih Afdeling --</option>
                    @foreach ($afdeling ?? [] as $item)
                    <option value="{{ $item->id }}" class="py-2">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Plot Type Selection -->
            <!-- <div class="@if(!$selectedAfdeling) opacity-50 pointer-events-none @endif">
                <div class="flex space-x-4 items-center bg-white p-3 rounded-lg border border-gray-300">
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model.live="plotType" value="estate" class="form-radio text-blue-500 focus:ring-blue-500 h-5 w-5">
                        <span class="ml-2">Plot Estate</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model.live="plotType" value="blok" class="form-radio text-blue-500 focus:ring-blue-500 h-5 w-5">
                        <span class="ml-2">Plot Blok</span>
                    </label>
                </div>
            </div> -->
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow-md">
            <div id="map" wire:ignore class="h-[600px] w-full rounded-lg"></div>
        </div>
    </div>

</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        const map = L.map('map').setView([-2.2653013566283, 111.65335780362], 13);
        let tphLayer = null;
        let plotLayer = null;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        function updateTPHMarkers(value) {
            if (tphLayer) {
                map.removeLayer(tphLayer);
                tphLayer = null;
            }

            if (value && Array.isArray(value.features) && value.features.length > 0) {
                tphLayer = L.geoJSON(value, {
                    pointToLayer: function(feature, latlng) {
                        return L.circleMarker(latlng, {
                            radius: 8,
                            fillColor: "#ff0000",
                            color: "#000",
                            weight: 1,
                            opacity: 1,
                            fillOpacity: 0.8
                        });
                    },
                    onEachFeature: function(feature, layer) {
                        const popupContent = `
                            <strong>TPH Info</strong><br>
                            Blok: ${feature.properties.blok}<br>
                            Ancak: ${feature.properties.ancak}<br>
                            TPH: ${feature.properties.tph}<br>
                            User: ${feature.properties.user_input}<br>
                            Datetime: ${feature.properties.datetime}
                        `;
                        layer.bindPopup(popupContent);
                    }
                }).addTo(map);
            }
        }

        function updatePlotLayer(value) {
            if (plotLayer) {
                map.removeLayer(plotLayer);
                plotLayer = null;
            }

            if (value && value.features) {
                plotLayer = L.geoJSON(value, {
                    style: function(feature) {
                        return {
                            fillColor: feature.properties.estate ? '#ff7800' : '#3388ff',
                            weight: 2,
                            opacity: 1,
                            color: 'white',
                            dashArray: '3',
                            fillOpacity: 0.7
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
                                    fillOpacity: 0.9
                                });
                            },
                            mouseout: function(e) {
                                var layer = e.target;
                                layer.setStyle({
                                    weight: 2,
                                    color: 'white',
                                    dashArray: '3',
                                    fillOpacity: 0.7
                                });
                            }
                        });
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

    });
</script>
@endpush