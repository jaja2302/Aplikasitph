<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-gray-900">{{ $title }}</h1>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Date Picker -->
            <div class="space-y-2">
                <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input
                    type="date"
                    id="date"
                    wire:model.live="selectedDate"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                    @if($isProcessing) disabled @endif>
            </div>

            <!-- Regional Select -->
            <div class="space-y-2">
                <label for="regional" class="block text-sm font-medium text-gray-700">Regional</label>
                <select
                    id="regional"
                    wire:model.live="selectedRegional"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                    @if($isProcessing) disabled @endif>
                    <option value="">Pilih Regional</option>
                    @foreach($regional as $reg)
                    <option value="{{ $reg->id }}">{{ $reg->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Wilayah Select -->
            <div class="space-y-2">
                <label for="wilayah" class="block text-sm font-medium text-gray-700">Wilayah</label>
                <select
                    id="wilayah"
                    wire:model.live="selectedWilayah"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                    @if(!$wilayah || $isProcessing) disabled @endif>
                    <option value="">Pilih Wilayah</option>
                    @foreach($wilayah as $wil)
                    <option value="{{ $wil->id }}">{{ $wil->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Estate Select -->
            <div class="space-y-2">
                <label for="estate" class="block text-sm font-medium text-gray-700">Estate</label>
                <select
                    id="estate"
                    wire:model.live="selectedEstate"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                    @if(!$estate || $isProcessing) disabled @endif>
                    <option value="">Pilih Estate</option>
                    @foreach($estate as $est)
                    <option value="{{ $est->id }}">{{ $est->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Afdeling Select -->
            <div class="space-y-2">
                <label for="afdeling" class="block text-sm font-medium text-gray-700">Afdeling</label>
                <select
                    id="afdeling"
                    wire:model.live="selectedAfdeling"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                    @if(!$afdeling || $isProcessing) disabled @endif>
                    <option value="">Pilih Afdeling</option>
                    @foreach($afdeling as $afd)
                    <option value="{{ $afd->id }}">{{ $afd->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Blok Select -->
            <div class="space-y-2">
                <label for="blok" class="block text-sm font-medium text-gray-700">Blok</label>
                <select
                    id="blok"
                    wire:model.live="selectedBlok"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                    @if(!$blok || $isProcessing) disabled @endif>
                    <option value="">Pilih Blok</option>
                    @foreach($blok as $blk)
                    <option value="{{ $blk->id }}">{{ $blk->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden p-4">
        <div wire:ignore class="h-[600px]" id="map"></div>
    </div>

</div>

@push('styles')
<style>
    .mapboxgl-ctrl-top-right {
        top: 1rem !important;
        right: 1rem !important;
    }
</style>
@endpush

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
                            fillOpacity: 0.8
                        });
                    },
                    onEachFeature: function(feature, layer) {
                        const popupContent = `
                            <strong>TPH Info</strong><br>
                            Blok: ${feature.properties.blok}<br>
                            Ancak: ${feature.properties.ancak}<br>
                            TPH: ${feature.properties.tph}<br>
                            Petugas: ${feature.properties.user_input}<br>
                            Tanggal: ${feature.properties.tanggal}
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

                // Add toggle button
                const toggleBtn = L.DomUtil.create('button', '', container);
                toggleBtn.innerHTML = '▼';
                toggleBtn.style.float = 'right';
                toggleBtn.style.border = 'none';
                toggleBtn.style.background = 'none';
                toggleBtn.style.cursor = 'pointer';
                toggleBtn.style.padding = '0 5px';

                // Create content container
                const contentDiv = L.DomUtil.create('div', '', container);
                contentDiv.style.display = 'block'; // Initially visible

                // Toggle functionality
                let isVisible = true;
                toggleBtn.onclick = function() {
                    isVisible = !isVisible;
                    contentDiv.style.display = isVisible ? 'block' : 'none';
                    toggleBtn.innerHTML = isVisible ? '▼' : '▲';
                };

                // Update function for legend content
                this.update = function(legendInfo) {
                    let html = `<h4 style="margin:0 0 10px 0;font-weight:bold">${legendInfo.title}</h4>`;
                    html += `<p style="margin:0 0 10px 0">${legendInfo.description}</p>`;
                    html += `<p style="margin:0 0 10px 0">${legendInfo.tanggal}</p>`;
                    html += '<div style="border-top:1px solid #ccc;padding-top:10px">';
                    html += `<p style="margin:5px 0"><strong>Total TPH:</strong> ${legendInfo.Total_tph}</p>`;

                    if (legendInfo.user_input && legendInfo.user_input.length > 0) {
                        html += '<p style="margin:5px 0"><strong>Petugas:</strong></p>';
                        html += '<ul style="margin:5px 0;padding-left:20px">';
                        legendInfo.user_input.forEach(user => {
                            html += `<li>${user}</li>`;
                        });
                        html += '</ul>';
                    }

                    if (legendInfo.blok_tersidak && legendInfo.blok_tersidak.length > 0) {
                        html += '<p style="margin:5px 0"><strong>Blok Tersidak:</strong></p>';
                        html += '<ul style="margin:5px 0;padding-left:20px">';
                        legendInfo.blok_tersidak.forEach(blok => {
                            html += `<li>${blok}</li>`;
                        });
                        html += '</ul>';
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
    });
</script>
@endpush