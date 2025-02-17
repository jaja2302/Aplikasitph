<x-layouts.app>
    <style>
        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.3s ease-out;
        }
    </style>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-md p-2">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                                Maps TPH
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Filter Data</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Regional Select -->
                    <div class="space-y-2">
                        <label for="regional" class="flex items-center text-sm font-medium text-gray-700">
                            Regional
                        </label>
                        <select id="regional" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                            <option value="">Pilih Regional</option>
                        </select>
                    </div>

                    <!-- Other select elements similar to regional -->
                    <div class="space-y-2">
                        <label for="wilayah" class="flex items-center text-sm font-medium text-gray-700">
                            Wilayah
                        </label>
                        <select id="wilayah" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" disabled>
                            <option value="">Pilih Wilayah</option>
                        </select>
                    </div>

                    <!-- Add Estate, Afdeling, and Blok selects similarly -->

                    <!-- Estate Select -->
                    <div class="space-y-2">
                        <label for="estate" class="flex items-center text-sm font-medium text-gray-700">
                            Estate
                        </label>
                        <select id="estate" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" disabled>
                            <option value="">Pilih Estate</option>
                        </select>
                    </div>

                    <!-- Afdeling Select -->
                    <div class="space-y-2">
                        <label for="afdeling" class="flex items-center text-sm font-medium text-gray-700">
                            Afdeling
                        </label>
                        <select id="afdeling" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" disabled>
                            <option value="">Pilih Afdeling</option>
                        </select>
                    </div>

                    <!-- Blok Select -->
                    <div class="space-y-2">
                        <label for="blok" class="flex items-center text-sm font-medium text-gray-700">
                            Blok
                        </label>
                        <select id="blok" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" disabled>
                            <option value="">Pilih Blok</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Peta Lokasi TPH</h3>
                    <div class="flex space-x-3">
                        <!-- Layer Controls -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Layer:</span>
                            <select id="mapLayer" class="form-select text-sm border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="satellite">Satellite</option>
                                <option value="osm">OpenStreetMap</option>
                            </select>
                        </div>

                        <!-- Opacity Control -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Opacity:</span>
                            <input type="range" id="mapOpacity" class="form-range" min="0" max="100" value="70">
                            <span id="opacityValue" class="text-sm text-gray-500">70%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="relative">
                <div class="h-[600px]" id="map"></div>
                <!-- Loading Overlay -->
                <div id="mapLoader" class="hidden absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
                    <div class="flex flex-col items-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600"></div>
                        <span class="mt-2 text-sm text-gray-600">Memuat data...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend/Info Section -->
        <div id="legendInfo" class="bg-white shadow-sm rounded-lg p-6 hidden">
            <div class="space-y-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Klik Nomor TPH untuk melihat detail data TPH di peta</h3>
                    <button
                        id="resetMapView"
                        class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors">
                        Reset View
                    </button>
                </div>

                <!-- Basic Info Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Detail Estate <span id="estateName"></span> Afdeling <span id="afdelingName"></span></h3>
                        </div>

                        <!-- Stats Cards -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm text-gray-600">Total Blok</div>
                                <div class="text-2xl font-bold text-gray-900">
                                    <span id="totalBlok">0</span>
                                    (<span class="text-green-500" id="verifiedBlok">0</span>/<span class="text-red-500" id="unverifiedBlok">0</span>)
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm text-gray-600">Total TPH</div>
                                        <span class="text-sm font-medium text-gray-900" id="progressPercentage">0%</span>
                                    </div>

                                    <div class="text-2xl font-bold text-gray-900">
                                        <span id="totalTPH">0</span>
                                        (<span class="text-green-500" id="verifiedTPH">0</span>/<span class="text-red-500" id="unverifiedTPH">0</span>)
                                    </div>

                                    <div class="relative">
                                        <div class="h-1.5 rounded-full bg-gray-200">
                                            <div class="h-1.5 rounded-full bg-green-500 transition-all duration-300" id="progressBar" style="width: 0%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Petugas Section -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Petugas</h4>
                        <div class="space-y-2" id="petugasList">
                            <!-- Petugas will be populated here -->
                        </div>
                    </div>
                </div>

                <!-- Blok Status Section -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Detail Blok per Titik TPH</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 overflow-y-auto max-h-[600px]">
                    <!-- Blok Terinput -->
                    <div class="bg-green-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-green-900 mb-3">Blok Terinput</h4>
                        <div class="grid grid-cols-1 gap-3" id="blokTerinput">
                            <!-- Blok terinput will be populated here -->
                        </div>
                    </div>

                    <!-- Blok Belum Terinput -->
                    <div class="bg-red-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-red-900 mb-3">Blok Belum Terinput</h4>
                        <div class="grid grid-cols-1 gap-3" id="blokBelumTerinput">
                            <!-- Blok belum terinput will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script type="module">
        let canedit = @json($canedit);
        // console.log(canedit); // Cek di console browser apakah nilai muncul
        // Move focusOnTPH outside the DOMContentLoaded event listener
        window.focusOnTPH = function(blok, tphNumber) {
            const estateSelect = document.getElementById('estate');
            const afdelingSelect = document.getElementById('afdeling');
            const map = window.leafletMap; // We'll store the map reference globally

            const estateId = estateSelect.value;
            const afdelingId = afdelingSelect.value;

            fetch(`{{ route('dashboard.tph-coordinates', ['estateId' => ':estateId', 'afdelingId' => ':afdelingId']) }}`
                    .replace(':estateId', estateId)
                    .replace(':afdelingId', afdelingId))
                .then(response => response.json())
                .then(data => {
                    const tph = data.features.find(feature =>
                        feature.properties.blok === blok &&
                        feature.properties.tph === parseInt(tphNumber)
                    );

                    if (tph) {
                        const coords = tph.geometry.coordinates;
                        map.setView([coords[1], coords[0]], 18);

                        // Highlight the TPH
                        if (window.highlightMarker) {
                            map.removeLayer(window.highlightMarker);
                        }

                        window.highlightMarker = L.circleMarker([coords[1], coords[0]], {
                            radius: 12,
                            fillColor: '#FFD700',
                            color: '#000',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.8
                        }).addTo(map);
                    }
                });
        };
        // Pastikan fungsi ini berada di luar blok dan dalam global scope
        // Modified hapusTPH function for dynamic marker removal
        window.hapusTPH = function(id, tphNumber) {
            if (confirm(`Apakah Anda yakin ingin menghapus TPH ${tphNumber} ini?`)) {
                fetch(`{{ route('dashboard.delete-tph', ['id' => ':id']) }}`.replace(':id', id), {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Find and remove the marker from the map
                        const map = window.leafletMap;
                        const tphLayer = map._layers[Object.keys(map._layers).find(key =>
                            map._layers[key].feature &&
                            map._layers[key].feature.properties &&
                            map._layers[key].feature.properties.id === id
                        )];

                        if (tphLayer) {
                            // Remove the marker from the map
                            map.removeLayer(tphLayer);

                            // Close any open popup
                            map.closePopup();

                            // Update statistics in the legend
                            updateStatisticsAfterDeletion(tphNumber);
                        }

                        // Show success message
                        showNotification(data.message || 'TPH berhasil dihapus');
                    })
                    .catch(error => {
                        console.error("Error deleting TPH:", error);
                        showNotification("Terjadi kesalahan: " + error.message);
                    });
            }
        };

        // Function to update statistics after TPH deletion
        function updateStatisticsAfterDeletion(tphNumber) {
            // Update total TPH count
            const totalTPHElement = document.getElementById('totalTPH');
            const currentTotal = parseInt(totalTPHElement.textContent);
            totalTPHElement.textContent = Math.max(0, currentTotal - 1);

            // Update verified/unverified TPH counts
            const verifiedTPHElement = document.getElementById('verifiedTPH');
            const unverifiedTPHElement = document.getElementById('unverifiedTPH');
            const currentVerified = parseInt(verifiedTPHElement.textContent);
            const currentUnverified = parseInt(unverifiedTPHElement.textContent);

            // Cari button TPH dengan cara yang benar
            // Mencari semua button di blok verified dan unverified
            const verifiedButtons = document.querySelectorAll('#blokTerinput button');
            const unverifiedButtons = document.querySelectorAll('#blokBelumTerinput .px-1\\.5');

            // Cek apakah TPH ada di verified atau unverified
            const wasVerified = Array.from(verifiedButtons).some(button => button.textContent.trim() === tphNumber.toString());

            // Update counter yang sesuai
            if (wasVerified) {
                verifiedTPHElement.textContent = Math.max(0, currentVerified - 1);
            } else {
                unverifiedTPHElement.textContent = Math.max(0, currentUnverified - 1);
            }

            // Update progress percentage
            const newTotal = Math.max(0, currentTotal - 1);
            const newVerified = wasVerified ? Math.max(0, currentVerified - 1) : currentVerified;
            const progressPercentage = newTotal > 0 ? Math.round((newVerified / newTotal) * 100) : 0;

            document.getElementById('progressPercentage').textContent = `${progressPercentage}%`;
            document.getElementById('progressBar').style.width = `${progressPercentage}%`;

            // Update TPH number di legend
            // Cari button TPH yang sesuai
            const tphButtons = document.querySelectorAll('button, span.px-1\\.5');
            let tphElement = null;

            for (const element of tphButtons) {
                if (element.textContent.trim() === tphNumber.toString()) {
                    tphElement = element;
                    break;
                }
            }

            if (tphElement) {
                const tphContainer = tphElement.closest('.flex.flex-col.gap-1');
                if (tphContainer) {
                    // Jika ini TPH terakhir di container, hapus seluruh container
                    const remainingTPHs = tphContainer.querySelectorAll('button, span.px-1\\.5').length;
                    if (remainingTPHs <= 1) {
                        tphContainer.remove();
                    } else {
                        // Jika bukan terakhir, hapus hanya button/span TPH nya saja
                        tphElement.remove();
                    }
                }
            }
        }

        // Enhanced notification function with better styling
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';
            const textColor = type === 'success' ? 'text-green-800' : 'text-red-800';

            notification.className = `fixed top-4 right-4 ${bgColor} border rounded-lg shadow-lg p-4 z-50 animate-fade-in-down`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 ${textColor} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success' 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'}
                    </svg>
                    <span class="${textColor} font-medium">${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('opacity-0', 'transition-opacity');
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Map initialization
            const map = L.map('map').setView([-2.2653013566283, 111.65335780362], 13);
            window.leafletMap = map; // Store map reference globally
            let tphLayer = null;
            let plotLayer = null;
            let labelMarkers = [];
            let currentFillOpacity = 0.7;

            // Base layers
            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            });

            const satelliteLayer = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '© Google'
            });

            // Add default satellite layer
            satelliteLayer.addTo(map);

            // Layer control handlers
            const layerSelect = document.getElementById('mapLayer');
            layerSelect.addEventListener('change', (e) => {
                if (e.target.value === 'osm') {
                    map.removeLayer(satelliteLayer);
                    map.addLayer(osmLayer);
                } else {
                    map.removeLayer(osmLayer);
                    map.addLayer(satelliteLayer);
                }
            });

            // Opacity control
            const opacitySlider = document.getElementById('mapOpacity');
            const opacityValue = document.getElementById('opacityValue');

            opacitySlider.addEventListener('input', (e) => {
                const value = e.target.value;
                opacityValue.textContent = value + '%';
                currentFillOpacity = value / 100;

                if (plotLayer) {
                    plotLayer.eachLayer((layer) => {
                        layer.setStyle({
                            fillOpacity: currentFillOpacity
                        });
                    });
                }
            });

            // Select elements
            const regionalSelect = document.getElementById('regional');
            const wilayahSelect = document.getElementById('wilayah');
            const estateSelect = document.getElementById('estate');
            const afdelingSelect = document.getElementById('afdeling');
            const blokSelect = document.getElementById('blok');

            // Loading state handlers
            function showLoader() {
                document.getElementById('mapLoader').classList.remove('hidden');
            }

            function hideLoader() {
                document.getElementById('mapLoader').classList.add('hidden');
            }


            // Populate select functions
            async function populateRegional() {
                const data = await fetchData("{{ route('locations.regional') }}");
                regionalSelect.innerHTML = '<option value="">Pilih Regional</option>';
                data.forEach(item => {
                    regionalSelect.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
                });
            }

            async function populateWilayah(regionalId) {
                const data = await fetchData(`{{ route('locations.wilayah', ['regionalId' => ':regionalId']) }}`.replace(':regionalId', regionalId));
                wilayahSelect.innerHTML = '<option value="">Pilih Wilayah</option>';
                data.forEach(item => {
                    wilayahSelect.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
                });
                wilayahSelect.disabled = false;
            }

            async function populateEstate(wilayahId) {
                const data = await fetchData(`{{ route('locations.estate', ['wilayahId' => ':wilayahId']) }}`.replace(':wilayahId', wilayahId));
                estateSelect.innerHTML = '<option value="">Pilih Estate</option>';
                data.forEach(item => {
                    estateSelect.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
                });
                estateSelect.disabled = false;
            }

            async function populateAfdeling(estateId) {
                const data = await fetchData(`{{ route('locations.afdeling', ['estateId' => ':estateId']) }}`.replace(':estateId', estateId));
                afdelingSelect.innerHTML = '<option value="">Pilih Afdeling</option>';
                data.forEach(item => {
                    afdelingSelect.innerHTML += `<option value="${item.id}">${item.abbr}</option>`;
                });
                afdelingSelect.disabled = false;
            }

            async function populateBlok(afdelingId) {
                const data = await fetchData(`{{ route('locations.blok', ['afdelingId' => ':afdelingId']) }}`.replace(':afdelingId', afdelingId));
                blokSelect.innerHTML = '<option value="">Pilih Blok</option>';
                data.forEach(item => {
                    blokSelect.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
                });
                blokSelect.disabled = false;
            }

            // Map update functions

            async function updateTPHMarkers(estateId, afdelingId, selectedBlokNama = null) {
                if (tphLayer) {
                    map.removeLayer(tphLayer);
                    tphLayer = null;
                }

                const url = new URL(`{{ route('dashboard.tph-coordinates', ['estateId' => ':estateId', 'afdelingId' => ':afdelingId']) }}`
                    .replace(':estateId', estateId)
                    .replace(':afdelingId', afdelingId));

                if (selectedBlokNama) {
                    url.searchParams.append('blokNama', selectedBlokNama);
                }

                const data = await fetchData(url);
                let hasValidBounds = false;


                if (data && Array.isArray(data.features) && data.features.length > 0) {
                    tphLayer = L.featureGroup();

                    L.geoJSON(data, {
                        pointToLayer: function(feature, latlng) {
                            const markerColor = feature.properties.status === 1 ? "#4CAF50" : "#FF0000";
                            return L.circleMarker(latlng, {
                                radius: 8,
                                fillColor: markerColor,
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
                            TPH: ${feature.properties.tph}<br>
                            Estate: ${feature.properties.estate}<br>
                            Afdeling: ${feature.properties.afdeling}<br>
                            User Input: ${feature.properties.user_input}<br>
                            ${canedit ? `
                                <button onclick="hapusTPH(${feature.properties.id}, ${feature.properties.tph})"
                                    class="bg-red-600 text-white px-3 py-1 rounded mt-2 text-sm hover:bg-red-700">
                                    Hapus TPH
                                </button>
                            ` : ''}
                        `;

                            layer.bindPopup(popupContent);
                        }
                    }).addTo(tphLayer);

                    map.addLayer(tphLayer);

                    // Set bounds based on TPH layer if available
                    if (tphLayer.getLayers().length > 0) {
                        try {
                            const bounds = tphLayer.getBounds();
                            if (bounds.isValid()) {
                                map.fitBounds(bounds);
                                hasValidBounds = true;
                            }
                        } catch (error) {
                            console.warn('TPH bounds not available:', error);
                        }
                    }

                }

                return hasValidBounds;
            }

            async function updatePlotLayer(afdelingId, selectedBlokNama = null) {
                if (plotLayer) {
                    map.removeLayer(plotLayer);
                    plotLayer = null;
                }

                labelMarkers.forEach(marker => map.removeLayer(marker));
                labelMarkers = [];

                const url = new URL(`{{ route('dashboard.plot-map', ['afdelingId' => ':afdelingId']) }}`.replace(':afdelingId', afdelingId));
                if (selectedBlokNama) {
                    url.searchParams.append('blokNama', selectedBlokNama);
                }

                const data = await fetchData(url);

                if (data && data.features && data.features.length > 0) {
                    plotLayer = L.geoJSON(data, {
                        style: function(feature) {
                            const isTersidak = feature.properties.tersidak;
                            return {
                                fillColor: isTersidak ? '#87CEEB' : '#FF5252',
                                weight: 2,
                                opacity: 1,
                                color: 'white',
                                dashArray: '3',
                                fillOpacity: currentFillOpacity
                            };
                        },
                        onEachFeature: function(feature, layer) {
                            // Popup content
                            const popupContent = `
                        <strong>Blok: ${feature.properties.nama}</strong>
                    `;
                            layer.bindPopup(popupContent);

                            // Hover effects
                            layer.on({
                                mouseover: function(e) {
                                    const layer = e.target;
                                    layer.setStyle({
                                        weight: 5,
                                        color: '#666',
                                        dashArray: '',
                                        fillOpacity: Math.min(currentFillOpacity + 0.2, 1.0)
                                    });
                                },
                                mouseout: function(e) {
                                    const layer = e.target;
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

                    // Try to fit bounds to plot layer
                    try {
                        const bounds = plotLayer.getBounds();
                        if (bounds.isValid()) {
                            map.fitBounds(bounds);
                            return true; // Successfully set bounds
                        }
                    } catch (error) {
                        console.warn('Plot bounds not available:', error);
                    }
                }

                return false; // Failed to set plot bounds
            }

            // Event listeners for selects
            regionalSelect.addEventListener('change', async function() {
                const regionalId = this.value;
                if (regionalId) {
                    showLoader();
                    try {
                        await populateWilayah(regionalId);
                    } finally {
                        hideLoader();
                    }
                }
            });

            wilayahSelect.addEventListener('change', async function() {
                const wilayahId = this.value;
                if (wilayahId) {
                    showLoader();
                    try {
                        await populateEstate(wilayahId);
                    } finally {
                        hideLoader();
                    }
                }
            });

            estateSelect.addEventListener('change', async function() {
                const estateId = this.value;
                if (estateId) {
                    showLoader();
                    try {
                        await populateAfdeling(estateId);
                    } finally {
                        hideLoader();
                    }
                }
            });

            afdelingSelect.addEventListener('change', async function() {
                const afdelingId = this.value;
                if (afdelingId) {
                    showLoader();
                    try {
                        await populateBlok(afdelingId);

                        // First try to update and set bounds with plot layer
                        const plotBoundsSet = await updatePlotLayer(afdelingId);

                        if (!plotBoundsSet) {
                            showNotification('Plot tidak tersedia. Menampilkan titik TPH jika tersedia.');
                            // Try to update and set bounds with TPH markers
                            const tphBoundsSet = await updateTPHMarkers(estateSelect.value, afdelingId);
                            if (!tphBoundsSet) {
                                showNotification('Tidak ada data TPH yang tersedia.');
                            }
                        } else {
                            // Still update TPH markers but don't use their bounds
                            await updateTPHMarkers(estateSelect.value, afdelingId);
                        }

                        await updateLegendInfo(estateSelect.value, afdelingId);
                    } catch (error) {
                        console.error('Error updating data:', error);
                        showNotification('Terjadi kesalahan saat memuat data.');
                    } finally {
                        hideLoader();
                    }
                }
            });

            blokSelect.addEventListener('change', async function() {
                const blokId = this.value;
                if (blokId) {
                    showLoader();
                    try {
                        const selectedBlokNama = this.options[this.selectedIndex].text;

                        // First try to update and set bounds with plot layer
                        const plotBoundsSet = await updatePlotLayer(afdelingSelect.value, selectedBlokNama);

                        if (!plotBoundsSet) {
                            showNotification('Plot tidak tersedia. Menampilkan titik TPH jika tersedia.');
                            // Try to update and set bounds with TPH markers
                            const tphBoundsSet = await updateTPHMarkers(estateSelect.value, afdelingSelect.value, selectedBlokNama);
                            if (!tphBoundsSet) {
                                showNotification('Tidak ada data TPH yang tersedia.');
                            }
                        } else {
                            // Still update TPH markers but don't use their bounds
                            await updateTPHMarkers(estateSelect.value, afdelingSelect.value, selectedBlokNama);
                        }

                        await updateLegendInfo(estateSelect.value, afdelingSelect.value, selectedBlokNama);
                    } catch (error) {
                        console.error('Error updating data:', error);
                        showNotification('Terjadi kesalahan saat memuat data.');
                    } finally {
                        hideLoader();
                    }
                }
            });

            // Initialize
            populateRegional();

            async function updateLegendInfo(estateId, afdelingId, selectedBlokNama = null) {
                const url = new URL(`{{ route('dashboard.legend-info', ['estateId' => ':estateId', 'afdelingId' => ':afdelingId']) }}`
                    .replace(':estateId', estateId)
                    .replace(':afdelingId', afdelingId));

                if (selectedBlokNama) {
                    url.searchParams.append('blokNama', selectedBlokNama);
                }

                const data = await fetchData(url);
                document.getElementById('legendInfo').classList.remove('hidden');
                document.getElementById('estateName').textContent = data.estate_name;
                document.getElementById('afdelingName').textContent = data.afdeling_name;

                // Update stats
                document.getElementById('totalBlok').textContent = data.total_blok_name_count;
                document.getElementById('verifiedBlok').textContent = data.verifiedblokcount;
                document.getElementById('unverifiedBlok').textContent = data.unveridblokcount;

                document.getElementById('totalTPH').textContent = data.total_tph;
                document.getElementById('verifiedTPH').textContent = data.total_tph_verif;
                document.getElementById('unverifiedTPH').textContent = data.total_tph_unverif;

                document.getElementById('progressPercentage').textContent = `${data.progress_percentage}%`;
                document.getElementById('progressBar').style.width = `${data.progress_percentage}%`;

                // Update petugas list
                const petugasList = document.getElementById('petugasList');
                petugasList.innerHTML = data.user_input.map(user => `
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span class="text-sm text-gray-600">${user}</span>
                    </div>
                `).join('');

                // Update blok lists
                updateBlokLists(data.tph_detail_per_blok);
            }

            function updateBlokLists(tphDetailPerBlok) {
                const blokTerinput = document.getElementById('blokTerinput');
                const blokBelumTerinput = document.getElementById('blokBelumTerinput');

                blokTerinput.innerHTML = '';
                blokBelumTerinput.innerHTML = '';

                tphDetailPerBlok.forEach(blok => {
                    if (blok.verified_tph > 0) {
                        blokTerinput.innerHTML += createBlokElement(blok, true);
                    }
                    if (blok.unverified_tph > 0) {
                        blokBelumTerinput.innerHTML += createBlokElement(blok, false);
                    }
                });
            }

            function createBlokElement(blok, isVerified) {
                const numbers = isVerified ? blok.verified_tph_numbers : blok.unverified_tph_numbers;
                const tphNumbers = numbers ? numbers.split(',').sort((a, b) => a - b) : [];

                return `
                    <div class="flex flex-col gap-1">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${isVerified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${blok.blok_kode} (${isVerified ? blok.verified_tph : blok.unverified_tph}/${blok.total_tph})
                        </span>
                        ${tphNumbers.length > 0 ? `
                            <div class="text-xs text-gray-600 break-words">
                                <span class="font-medium">TPH:</span>
                                <div class="flex flex-wrap gap-1">
                                    ${tphNumbers.map(number => `
                                        ${isVerified ? `
                                            <button onclick="focusOnTPH('${blok.blok_kode}', ${number})"
                                                class="px-1.5 py-0.5 bg-green-50 rounded hover:bg-green-100 transition-colors cursor-pointer">
                                                ${number}
                                            </button>
                                        ` : `
                                            <span class="px-1.5 py-0.5 bg-red-50 rounded">${number}</span>
                                        `}
                                    `).join('')}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
            }

            document.getElementById('resetMapView').addEventListener('click', function() {
                if (window.highlightMarker) {
                    map.removeLayer(window.highlightMarker);
                }

                if (plotLayer) {
                    map.fitBounds(plotLayer.getBounds());
                }
            });


        });
    </script>

</x-layouts.app>