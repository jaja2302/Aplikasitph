<div class="space-y-6 p-4 bg-gray-50 min-h-screen">
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
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
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
                // Comment out marker cluster initialization
                // tphLayer = L.markerClusterGroup({
                //     chunkedLoading: true,
                //     maxClusterRadius: 50,
                //     spiderfyOnMaxZoom: true,
                //     showCoverageOnHover: false,
                //     zoomToBoundsOnClick: true
                // });

                // Instead, use a regular feature group
                tphLayer = L.featureGroup();

                L.geoJSON(value, {
                    pointToLayer: function(feature, latlng) {
                        return L.circleMarker(latlng, {
                            radius: 8,
                            fillColor: "#ff0000",
                            color: "#000",
                            weight: 1,
                            opacity: 1,
                            fillOpacity: 0.8,
                            draggable: true
                        });
                    },
                    onEachFeature: function(feature, layer) {
                        const popupContent = `
                            <strong>TPH Info</strong><br>
                            Blok: ${feature.properties.blok}<br>
                            Ancak: ${feature.properties.ancak}<br>
                            TPH: ${feature.properties.tph}<br>
                            Petugas: ${feature.properties.user_input}<br>
                            Tanggal: ${feature.properties.tanggal}<br>
                            <button wire:click="editTPH(${feature.properties.id})"
                                style="background: #4CAF50; color: white; padding: 4px 8px; 
                                border: none; border-radius: 4px; cursor: pointer; margin-top: 8px;">
                                Edit TPH
                            </button>
                        `;
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



        // Custom Control untuk Legend Info
        L.Control.LegendInfo = L.Control.extend({
            onAdd: function(map) {
                const container = L.DomUtil.create('div', 'leaflet-control legend-info');
                container.style.backgroundColor = 'white';
                container.style.padding = '10px';
                container.style.borderRadius = '5px';
                container.style.boxShadow = '0 1px 5px rgba(0,0,0,0.4)';
                container.style.minWidth = '200px';
                container.style.maxWidth = '300px';
                container.style.maxHeight = '70vh';

                // Add toggle button
                const toggleBtn = L.DomUtil.create('button', '', container);
                toggleBtn.innerHTML = '▼';
                toggleBtn.style.float = 'right';
                toggleBtn.style.border = 'none';
                toggleBtn.style.background = 'none';
                toggleBtn.style.cursor = 'pointer';
                toggleBtn.style.padding = '0 5px';

                // Create content container with scroll
                const contentDiv = L.DomUtil.create('div', '', container);
                contentDiv.style.display = 'block';
                contentDiv.style.maxHeight = 'calc(70vh - 40px)';
                contentDiv.style.overflowY = 'auto';

                // Toggle functionality
                let isVisible = true;
                toggleBtn.onclick = function() {
                    isVisible = !isVisible;
                    contentDiv.style.display = isVisible ? 'block' : 'none';
                    toggleBtn.innerHTML = isVisible ? '▼' : '▲';
                };

                // Update function for legend content
                this.update = function(legendInfo) {
                    let html = `
                        <div class="legend-header" style="position:sticky;top:0;background:white;padding-bottom:10px;z-index:1">
                            <h4 style="margin:0 0 10px 0;font-weight:bold">${legendInfo.title}</h4>
                            <p style="margin:0 0 10px 0">${legendInfo.description}</p>
                            <p style="margin:0 0 10px 0">${legendInfo.tanggal}</p>
                            <p style="margin:5px 0"><strong>Total TPH:</strong> ${legendInfo.Total_tph}</p>
                        </div>`;

                    html += '<div style="border-top:1px solid #ccc;padding-top:10px">';

                    if (legendInfo.user_input && legendInfo.user_input.length > 0) {
                        html += '<div style="margin-bottom:10px">';
                        html += '<p style="margin:5px 0"><strong>Petugas:</strong></p>';
                        html += '<ul style="margin:5px 0;padding-left:20px;max-height:150px;overflow-y:auto">';
                        legendInfo.user_input.forEach(user => {
                            html += `<li>${user}</li>`;
                        });
                        html += '</ul></div>';
                    }

                    if (legendInfo.blok_tersidak && legendInfo.blok_tersidak.length > 0) {
                        html += '<div>';
                        html += '<p style="margin:5px 0"><strong>Blok Tersidak:</strong></p>';
                        html += '<ul style="margin:5px 0;padding-left:20px;max-height:200px;overflow-y:auto">';
                        legendInfo.blok_tersidak.forEach(blok => {
                            html += `<li>${blok}</li>`;
                        });
                        html += '</ul></div>';
                    }

                    html += '</div>';
                    contentDiv.innerHTML = html;
                };

                // Prevent map click events
                L.DomEvent.disableClickPropagation(container);
                L.DomEvent.disableScrollPropagation(container);

                return container;
            }
        });

        // Create legend control
        const legendControl = new L.Control.LegendInfo({
            position: 'bottomright'
        });
        map.addControl(legendControl);

        // Watch for changes in legendInfo
        @this.watch('legendInfo', value => {
            if (value) {
                legendControl.update(value);
            }
        });

        // Add CSS to your styles
        const style = document.createElement('style');
        style.textContent = `
            .blok-label {
                background: none;
                border: none;
            }
            .blok-label div {
                text-align: center;
            }
        `;
        document.head.appendChild(style);
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