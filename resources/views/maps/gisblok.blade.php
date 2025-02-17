<x-layouts.app>
    <!-- Update the buttons and result div section -->
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">GIS Blok</h1>

        <div class="mb-4">
            <select name="estate" id="estate" class="select select-bordered w-full max-w-xs mr-2">
                <option value="">Pilih Estate</option>
                @foreach ($list_est as $estate)
                <option value="{{ $estate['id'] }}">{{ $estate['nama'] }}</option>
                @endforeach
            </select>

            <select name="afd" id="afd" class="select select-bordered w-full max-w-xs mr-2">
                <option value="">Pilih Afdeling</option>
            </select>
        </div>

        <!-- # Bagian HTML -->
        <div class="flex flex-wrap gap-2 mb-4">
            <button class="btn btn-primary" id="button">
                Show Map
            </button>
            <button class="btn btn-info" id="buttonCheck" disabled>
                Check Latest Update
            </button>
            <button class="btn btn-warning" id="buttonFetch" disabled>
                Fetch & Sync Data
            </button>
            <button class="btn btn-secondary" id="buttonTriggerZip" disabled>
                Generate ZIP Data
            </button>
        </div>

        <!-- Result Container -->
        <div id="fetchDataRes" class="mb-4"></div>

        <!-- Map Container -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <div id="map" style="height: 600px; width: 100%;"></div>
        </div>
    </div>

    <script type="module">
        var map = L.map('map', {
            editable: true
        }).setView([-2.2745234, 111.61404248], 11);

        var googleSatellite = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 22,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        var drawnItems = new L.FeatureGroup().addTo(map);
        var markersLayer = L.layerGroup().addTo(map);
        var ghostMarkersLayer = L.layerGroup().addTo(map);
        var currentEditingPolygon = null;

        function addGhostMarker(latlng) {
            L.circleMarker(latlng, {
                radius: 3,
                color: 'gray',
                fillColor: 'darkgray',
                fillOpacity: 0.5,
                weight: 1
            }).addTo(ghostMarkersLayer);
        }

        // Make toggleEdit globally available
        window.toggleEdit = function(polygon) {
            if (currentEditingPolygon) {
                currentEditingPolygon.disableEdit();
                if (currentEditingPolygon === polygon) {
                    currentEditingPolygon = null;
                    return;
                }
            }
            currentEditingPolygon = polygon;
            polygon.enableEdit();
            polygon.isEditing = true;
        }

        // Make savePolygon globally available
        window.savePolygon = function(polygon) {
            if (!polygon || !polygon.isEditing) {
                alert('Polygon is not in edit mode');
                return;
            }

            let coordinates = polygon.getLatLngs()[0].map(function(latLng) {
                return {
                    "lat": parseFloat(latLng.lat),
                    "lon": parseFloat(latLng.lng)
                };
            });

            if (coordinates[0].lat !== coordinates[coordinates.length - 1].lat ||
                coordinates[0].lon !== coordinates[coordinates.length - 1].lon) {
                coordinates.push(coordinates[0]);
            }

            $.ajax({
                url: "{{ route('gis.savePlotsblok') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    nama: polygon.blockName,
                    coordinates: coordinates
                },
                success: function(response) {
                    alert('Changes saved successfully');
                    polygon.disableEdit();
                    polygon.isEditing = false;
                    currentEditingPolygon = null;
                    polygon.closePopup();
                    $('#button').click(); // Refresh display
                },
                error: function(xhr, status, error) {
                    console.error('Save failed:', xhr.responseJSON);
                    alert('Failed to save changes: ' + (xhr.responseJSON?.error || error));
                }
            });
        }

        $('#button').click(function() {
            if (currentEditingPolygon) {
                currentEditingPolygon.disableEdit();
                currentEditingPolygon = null;
            }
            var estate = $('#estate').val();
            var afdeling = $('#afd').val();

            if (!estate) {
                alert('Please select an estate first');
                return;
            }
            if (!afdeling) {
                alert('Please select an afdeling first');
                return;
            }

            ghostMarkersLayer.clearLayers();

            $.ajax({
                url: "{{ route('gis.getPlotsblok') }}",
                method: 'get',
                data: {
                    estate: estate,
                    afdeling: afdeling
                },
                success: function(result) {
                    drawPlots(result.plots);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed');
                    console.error(status + ': ' + error);
                }
            });
        });

        function drawPlots(plots) {
            drawnItems.clearLayers();
            markersLayer.clearLayers();
            ghostMarkersLayer.clearLayers();
            let bounds = [];

            plots.features.forEach(function(feature) {
                let coordinates = feature.geometry.coordinates[0];
                let blockName = feature.properties.nama;

                if (coordinates && coordinates.length > 0) {
                    let polygon = L.polygon(coordinates, {
                        color: 'blue',
                        fillOpacity: 0.5,
                        weight: 2
                    });

                    polygon.blockName = blockName;

                    // Update popup content to use window.toggleEdit and window.savePolygon
                    let popupContent = `
                    <div>
                        <strong>${blockName}</strong><br>
                        <button onclick="window.toggleEdit(window.currentPolygon)" class="btn btn-sm btn-primary mt-2">
                            Toggle Edit
                        </button>
                        <button onclick="window.savePolygon(window.currentPolygon)" class="btn btn-sm btn-success mt-2 ml-2">
                            Save
                        </button>
                    </div>
                `;

                    polygon.bindPopup(popupContent);

                    polygon.on('popupopen', function() {
                        window.currentPolygon = this;
                    });

                    polygon.addTo(drawnItems);
                    bounds = bounds.concat(coordinates);
                }
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds);
            }
        }

        $('#downloadButton').click(function() {
            let estate = $('#estate').val();
            let afdeling = $('#afd').val();

            if (!estate) {
                alert('Please select an estate first');
                return;
            }
            if (!afdeling) {
                alert('Please select an afdeling first');
                return;
            }

            $.ajax({
                url: "{{ route('gis.getPlotsblok') }}",
                method: 'get',
                data: {
                    estate: estate,
                    afdeling: afdeling
                },
                success: function(result) {
                    downloadGeoJSON(result.plots, estate, afdeling);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed');
                    console.error(status + ': ' + error);
                }
            });
        });

        function downloadGeoJSON(plots, estate, afdeling) {
            let blob = new Blob([JSON.stringify(plots, null, 2)], {
                type: 'application/json'
            });
            let url = URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = `plots_${estate}_${afdeling}.geojson`;
            a.click();
        }

        // Ganti handler vertex yang lama dengan yang baru
        map.on('editable:vertex:click', function(e) {
            let allVertices = [];
            let latlngs = e.vertex.editor.getLatLngs();

            // Fungsi rekursif untuk mendapatkan semua vertex
            function getAllVertices(arr) {
                arr.forEach(function(item) {
                    if (Array.isArray(item)) {
                        getAllVertices(item);
                    } else {
                        allVertices.push(item);
                    }
                });
            }

            getAllVertices(latlngs);

            // Sekarang kita punya jumlah vertex yang benar
            if (allVertices.length > 4) { // Masih memastikan minimal 3 vertex setelah penghapusan
                // Tambahkan ghost marker sebelum menghapus vertex
                addGhostMarker([e.vertex.latlng.lat, e.vertex.latlng.lng]);
                e.vertex.delete();
            } else {
                alert('Cannot delete vertex: Polygon must have at least 3 points');
            }
        });

        // Handler untuk menambah vertex (double click pada edge)
        map.on('editable:vertex:new', function(e) {
            ghostMarkersLayer.clearLayers(); // Clear ghost markers when adding new vertex
        });

        // Handler untuk menggeser vertex
        map.on('editable:vertex:drag', function(e) {
            // Optional: Bisa ditambahkan logika untuk ghost markers saat drag
        });

        // Handler ketika editing selesai
        map.on('editable:editing', function(e) {
            // Optional: tambahkan logika validasi jika diperlukan
        });

        // Add this code before the existing map initialization
        $('#estate').change(function() {
            var estate = $(this).val();
            var afdelingSelect = $('#afd');

            // Clear current options
            afdelingSelect.empty();
            afdelingSelect.append('<option value="">Pilih Afdeling</option>');

            if (estate) {
                $.ajax({
                    url: "{{ route('gis.getAfdeling') }}",
                    method: 'get',
                    data: {
                        estate: estate
                    },
                    success: function(afdelings) {
                        afdelings.forEach(function(afd) {
                            afdelingSelect.append(
                                $('<option></option>').val(afd.id).text(afd.abbr)
                            );
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to fetch afdelings:', error);
                    }
                });
            }
        });


        // Fungsi untuk menampilkan loading state
        function setButtonLoading(button, isLoading) {
            if (isLoading) {
                button.prop('disabled', true);
                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
            } else {
                button.prop('disabled', false);
                button.html(button.data('original-text'));
            }
        }

        // Simpan text asli button saat dokumen ready
        $(document).ready(function() {
            $('#buttonCheck, #buttonDownload, #buttonFetch').each(function() {
                $(this).data('original-text', $(this).html());
            });
        });

        // Check Latest Update Handler
        $('#buttonCheck').click(function() {
            const button = $(this);
            const resultDiv = $('#fetchDataRes');

            setButtonLoading(button, true);

            $.ajax({
                url: "https://tph.srs-ssms.com/api/sync/tph/checkLastupdate",
                method: 'GET',
                success: function(result) {
                    console.log(result);

                    if (result.status === 200) {
                        resultDiv.html(`
                       <div class="alert alert-info mt-3">
                            <strong>Last Update:</strong> ${result.last_update}
                        </div>
                    `);
                    } else {
                        resultDiv.html(`
                <div class="alert alert-warning mt-3">
                    <strong>No data found</strong>
                </div>
            `);
                    }
                },

                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                    resultDiv.html(`
                <div class="alert alert-danger mt-3">
                    <strong>Error!</strong> Failed to check update
                </div>
            `);
                },
                complete: function() {
                    setButtonLoading(button, false);
                }
            });
        });



        // Fetch & Sync Data Handler
        $('#buttonFetch').click(function() {
            const button = $(this);
            const resultDiv = $('#fetchDataRes');

            setButtonLoading(button, true);

            $.ajax({
                url: "{{ route('fetch-data') }}",
                method: 'GET',
                success: function(result) {
                    if (result.status === 200) {
                        const progressHtml = `
                    <div class="mb-2">
                        <strong>Processed:</strong> ${result.processed_rows} / ${result.total_rows} records
                    </div>
                `;

                        resultDiv.html(`
                    <div class="alert alert-success mt-3">
                        <strong>Success!</strong> ${result.message}<br>
                        ${progressHtml}
                        <div class="text-sm mt-2">
                            ${result.zip_info ? `<strong>ZIP File:</strong> ${result.zip_info.file_name}<br>
                            <strong>Last Modified:</strong> ${result.zip_info.last_modified}` : ''}
                        </div>
                    </div>
                `);
                    } else {
                        resultDiv.html(`
                    <div class="alert alert-warning mt-3">
                        <strong>Warning!</strong> ${result.message}
                    </div>
                `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                    let errorMessage = 'Failed to sync data';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    resultDiv.html(`
                <div class="alert alert-danger mt-3">
                    <strong>Error!</strong> ${errorMessage}
                </div>
            `);
                },
                complete: function() {
                    setButtonLoading(button, false);
                }
            });
        });

        // Tambahkan ke JavaScript yang ada

        // Generate ZIP Data Handler
        $('#buttonTriggerZip').click(function() {
            const button = $(this);
            const resultDiv = $('#fetchDataRes');

            setButtonLoading(button, true);

            $.ajax({
                url: "https://tph.srs-ssms.com/api/sync/download",
                method: 'GET',
                success: function(result) {
                    if (result.status === 200) {
                        resultDiv.html(`
                    <div class="alert alert-success mt-3">
                        <strong>Success!</strong> ${result.message}<br>
                        Total Records: ${result.total_rows}<br>
                        <div class="text-sm mt-2">
                            ZIP generation has started. You can check the progress and download 
                            the file when it's ready using the "Download Data" button.
                        </div>
                    </div>
                `);
                    } else {
                        resultDiv.html(`
                    <div class="alert alert-warning mt-3">
                        <strong>Warning!</strong> ${result.message}
                    </div>
                `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                    let errorMessage = 'Failed to start ZIP generation';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    resultDiv.html(`
                <div class="alert alert-danger mt-3">
                    <strong>Error!</strong> ${errorMessage}
                </div>
            `);
                },
                complete: function() {
                    setButtonLoading(button, false);
                }
            });
        });

        // Add to document ready
        $(document).ready(function() {
            $('#buttonCheck, #buttonFetch, #buttonTriggerZip').each(function() {
                $(this).data('original-text', $(this).html());
            });
        });
    </script>

</x-layouts.app>