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
            <!-- Add date picker before regional selection -->
            <div>
                <input
                    type="date"
                    wire:model.live="selectedDate"
                    class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>

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

            <!-- select blok based afdeling -->
            <div>
                <select wire:model.live="selectedBlok" class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-100 disabled:text-gray-500" @if(!$blok) disabled @endif>
                    <option value="" class="text-gray-500">-- Pilih Blok --</option>
                    @foreach ($blok ?? [] as $item)
                    <option value="{{ $item->id }}" class="py-2">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow-md">
            <div id="map" wire:ignore class="h-[600px] w-full rounded-lg"></div>
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

    });
</script>
@endpush