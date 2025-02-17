<x-layouts.app>

    <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded-md">
        <h2 class="font-semibold text-lg">Manajemen TPH</h2>
        <p class="mt-2">Gunakan fitur ini untuk menghapus atau menambahkan TPH baru. Pastikan Anda memahami tindakan yang akan dilakukan sebelum melanjutkan.</p>
        <p class="mt-1 font-semibold">⚠️ Penghapusan TPH bersifat permanen dan tidak dapat dikembalikan.</p>
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
                <!-- Button Tambah TPH -->
                <div class="space-y-2 flex items-end">
                    <button id="btnAddTph"
                        onclick="showAddTphModal()"
                        disabled
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        Tambah TPH
                    </button>
                </div>
            </div>
        </div>
        <div class="relative">
            <!-- Move the page size selector above the table -->

            <div class="overflow-x-auto">
                <!-- Single table container -->
                <div class="mb-4 flex items-center gap-4">
                    <!-- Page Size -->
                    <div class="flex items-center gap-2">
                        <label for="pageSize" class="text-sm font-medium text-gray-700">Show</label>
                        <select id="pageSize" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="300">300</option>
                        </select>
                        <span class="text-sm text-gray-700">entries</span>
                    </div>

                    <!-- Delete Button -->
                    <button id="batchDeleteBtn"
                        onclick="batchDeleteTph()"
                        class="hidden bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete Selected (0)
                    </button>
                </div>


                <div class="overflow-y-auto max-h-[500px]">

                    <table id="mainTable" class="min-w-full table-fixed border border-gray-300">
                        <!-- Table content will be populated by JavaScript -->
                    </table>
                </div>
            </div>
        </div>
        <!-- // Tambahkan HTML modal di blade Anda -->
        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 id="modalTitle" class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Hapus</h3>
                        <div class="mt-2">
                            <p id="modalMessage" class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus data ini? Data yang dihapus tidak dapat dikembalikan.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmDelete"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                    <button type="button" id="cancelDelete"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
        <!-- Modal Tambah TPH -->
        <div id="addTphModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Tambah TPH Baru</h3>
                        <div class="mt-2 space-y-4">
                            <!-- Form fields -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Regional</label>
                                <input type="text" id="modalRegional" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50" readonly>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estate</label>
                                <input type="text" id="modalEstate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50" readonly>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Afdeling</label>
                                <input type="text" id="modalAfdeling" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50" readonly>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Blok</label>
                                <input type="text" id="modalBlok" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tahun tanam</label>
                                <input type="text" id="modalTahunTanam" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan Tahun Tanam">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor Ancak</label>
                                <input type="text" id="modalNomorAncak" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan nomor Ancak">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor TPH</label>
                                <input type="text" id="modalNomorTph" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan nomor TPH">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="saveTph()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="hideAddTphModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        // Select elements
        $(document).ready(function() {
            const regionalSelect = document.getElementById('regional');
            const wilayahSelect = document.getElementById('wilayah');
            const estateSelect = document.getElementById('estate');
            const afdelingSelect = document.getElementById('afdeling');
            const blokSelect = document.getElementById('blok');

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

            // Event listeners for selects
            regionalSelect.addEventListener('change', async function() {
                const regionalId = this.value;
                if (regionalId) {
                    showLoader();
                    try {
                        await populateWilayah(regionalId);
                        await updateTable();
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
                        // await updateTable();
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
                        await updateTable();
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
                        await updateTable();

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
                        await updateTable();
                    } catch (error) {
                        console.error('Error updating data:', error);
                        showNotification('Terjadi kesalahan saat memuat data.');
                    } finally {
                        hideLoader();
                    }
                }
            });
            // Tambahkan setelah semua event listener yang sudah ada
            window.updateTable = async function(page = 1) {
                showLoader();
                try {
                    const pageSize = document.getElementById('pageSize').value;
                    const params = new URLSearchParams({
                        page: page,
                        per_page: pageSize, // Add this line to include page size
                        regional_id: regionalSelect.value || '',
                        estate_id: estateSelect.value || '',
                        afdeling_id: afdelingSelect.value || '',
                        blok_id: blokSelect.value || ''
                    });

                    const response = await fetch(`{{ route('tph-management.get-tabel') }}?${params}`);
                    if (!response.ok) throw new Error('Network response was not ok');
                    const result = await response.json();

                    // Fungsi render table dipanggil dengan data result
                    renderTable(result);

                    // Update pagination
                    updatePagination(result.pagination);
                } catch (error) {
                    console.error('Error fetching table data:', error);
                    showNotification('Terjadi kesalahan saat memuat data tabel.');
                } finally {
                    hideLoader();
                }
            }

            // Add event listener for page size changes
            // Add event listener for page size changes
            document.getElementById('pageSize').addEventListener('change', function() {
                updateTable(1); // Reset to first page when changing page size
            });

            // Update the updatePagination function to include the current page size
            function updatePagination(pagination) {
                const paginationContainer = document.querySelector('.pagination-container');
                if (!paginationContainer) return;

                let paginationHTML = `
                <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                    <div class="flex flex-1 justify-between sm:hidden">
                        ${pagination.current_page > 1 
                            ? `<button onclick="updateTable(${pagination.current_page - 1})" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</button>`
                            : `<button class="relative inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">Previous</button>`
                        }
                        ${pagination.current_page < pagination.last_page
                            ? `<button onclick="updateTable(${pagination.current_page + 1})" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</button>`
                            : `<button class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">Next</button>`
                        }
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">${pagination.from || 0}</span> to <span class="font-medium">${pagination.to || 0}</span> of <span class="font-medium">${pagination.total}</span> results
                            </p>
                        </div>
                        <div>
                            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                <!-- First Page -->
                                <button onclick="updateTable(1)" 
                                        class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 ${pagination.current_page === 1 ? 'cursor-not-allowed bg-gray-100' : ''}"
                                        ${pagination.current_page === 1 ? 'disabled' : ''}>
                                    <span class="sr-only">First</span>
                                    ⟪
                                </button>
                                <!-- Previous Page -->
                                <button onclick="updateTable(${pagination.current_page - 1})"
                                        class="relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 ${pagination.current_page === 1 ? 'cursor-not-allowed bg-gray-100' : ''}"
                                        ${pagination.current_page === 1 ? 'disabled' : ''}>
                                    <span class="sr-only">Previous</span>
                                    ⟨
                                </button>
                                <!-- Current Page -->
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300">
                                    Page ${pagination.current_page} of ${pagination.last_page}
                                </span>
                                <!-- Next Page -->
                                <button onclick="updateTable(${pagination.current_page + 1})"
                                        class="relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 ${pagination.current_page === pagination.last_page ? 'cursor-not-allowed bg-gray-100' : ''}"
                                        ${pagination.current_page === pagination.last_page ? 'disabled' : ''}>
                                    <span class="sr-only">Next</span>
                                    ⟩
                                </button>
                                <!-- Last Page -->
                                <button onclick="updateTable(${pagination.last_page})"
                                        class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 ${pagination.current_page === pagination.last_page ? 'cursor-not-allowed bg-gray-100' : ''}"
                                        ${pagination.current_page === pagination.last_page ? 'disabled' : ''}>
                                    <span class="sr-only">Last</span>
                                    ⟫
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            `;

                paginationContainer.innerHTML = paginationHTML;
            }
            // Pindahkan fungsi renderTable ke luar updateTable
            function renderTable(result) {
                const table = document.getElementById('mainTable');
                const headers = [{
                        text: '<input type="checkbox" id="selectAll" class="h-4 w-4 rounded border-gray-300 cursor-pointer">',
                        width: 'w-12'
                    },
                    {
                        text: 'No',
                        width: 'w-16'
                    },
                    {
                        text: 'Regional',
                        width: 'w-24'
                    },
                    {
                        text: 'Estate',
                        width: 'w-24'
                    },
                    {
                        text: 'Afdeling',
                        width: 'w-28'
                    },
                    {
                        text: 'Blok',
                        width: 'w-24'
                    },
                    {
                        text: 'Nomor Ancak',
                        width: 'w-32'
                    },
                    {
                        text: 'Tahun Tanam',
                        width: 'w-32'
                    },
                    {
                        text: 'Nomor TPH',
                        width: 'w-28'
                    },
                    {
                        text: 'Status Marker',
                        width: 'w-32'
                    },
                    {
                        text: 'Aksi',
                        width: 'w-24'
                    }
                ];
                // header 
                const thead = `
                    <thead class="bg-white sticky top-0 z-10">
                        <tr>
                            ${headers.map(header => `
                                <th class="${header.width} px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300 bg-white">
                                    ${header.text}
                                </th>
                            `).join('')}
                        </tr>
                    </thead>
                `;


                // Create tbody
                const tbody = `
                <tbody class="bg-white divide-y divide-gray-200">
                    ${result.data.map((item, index) => {
                        const rowNumber = (result.pagination.current_page - 1) * result.pagination.per_page + index + 1;
                        return `
                            <tr>
                                 <td class="w-12 px-6 py-4 whitespace-nowrap text-center border border-gray-300">
                                    <input type="checkbox" name="tph_ids[]" value="${item.id}" 
                                        class="tph-checkbox h-4 w-4 rounded border-gray-300 cursor-pointer">
                                </td>
                                <td class="w-16 px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center border border-gray-300">${rowNumber}</td>
                                <td class="w-24 px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center border border-gray-300">${item.regional || '-'}</td>
                                <td class="w-24 px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center border border-gray-300">${item.estate || '-'}</td>
                                <td class="w-28 px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center border border-gray-300">${item.afdeling || '-'}</td>
                                <td class="w-24 px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center border border-gray-300">${item.nama_blok || '-'}</td>
                                <td class="w-32 px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center border border-gray-300">${item.ancak || '-'}</td>
                                <td class="w-32 px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center border border-gray-300">${item.tahun || '-'}</td>
                                <td class="w-28 px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center border border-gray-300">${item.tph || '-'}</td>
                                <td class="w-32 px-6 py-4 whitespace-nowrap text-sm font-medium text-center border border-gray-300 
                                    ${item.status === 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                                    ${item.status === 1 ? '✅' : '❌'}
                                </td>
                                <td class="w-24 px-6 py-4 whitespace-nowrap text-center text-sm font-medium border border-gray-300">
                                    <button onclick="deleteTph(${item.id})" 
                                            class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('')}
                </tbody>
            `;

                // Combine thead and tbody
                table.innerHTML = thead + tbody;
                setupBatchDeleteListeners();
            }



            function setupBatchDeleteListeners() {
                const selectAllCheckbox = document.getElementById('selectAll');
                const tphCheckboxes = document.getElementsByClassName('tph-checkbox');

                // Select All functionality
                selectAllCheckbox.addEventListener('change', function() {
                    Array.from(tphCheckboxes).forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateBatchDeleteButton();
                });

                // Individual checkbox functionality
                Array.from(tphCheckboxes).forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateBatchDeleteButton();
                        // Update select all checkbox
                        selectAllCheckbox.checked = Array.from(tphCheckboxes).every(cb => cb.checked);
                    });
                });
            }

            function updateBatchDeleteButton() {
                const selectedCount = document.querySelectorAll('.tph-checkbox:checked').length;
                const batchDeleteBtn = document.getElementById('batchDeleteBtn');

                if (selectedCount > 0) {
                    batchDeleteBtn.classList.remove('hidden');
                    batchDeleteBtn.textContent = `Delete Selected (${selectedCount})`;
                } else {
                    batchDeleteBtn.classList.add('hidden');
                }
            }

            window.handleDelete = async function(ids, isBatch = false) {
                const modal = document.getElementById('deleteModal');
                const confirmBtn = document.getElementById('confirmDelete');
                const cancelBtn = document.getElementById('cancelDelete');
                const modalMessage = document.getElementById('modalMessage');

                // Update modal message based on deletion type
                modalMessage.textContent = isBatch ?
                    `Apakah Anda yakin ingin menghapus ${ids.length} TPH yang dipilih? Data yang dihapus tidak dapat dikembalikan.` :
                    'Apakah Anda yakin ingin menghapus data ini? Data yang dihapus tidak dapat dikembalikan.';

                // Show modal
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                const executeDelete = async () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');

                    showLoader();
                    try {
                        const route = isBatch ?
                            '{{ route("tph-management.batch-delete") }}' :
                            '{{ route("tph-management.delete") }}';

                        const body = isBatch ? {
                            ids
                        } : {
                            id: ids
                        };

                        const response = await fetch(route, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(body)
                        });

                        if (!response.ok) throw new Error('Network response was not ok');
                        const result = await response.json();

                        if (result.success) {
                            showNotification(result.message || 'Data berhasil dihapus', 'success');
                            updateTable(document.querySelector('#pagination button.current-page')?.dataset?.page || 1);
                        } else {
                            throw new Error(result.message || 'Gagal menghapus data');
                        }
                    } catch (error) {
                        console.error('Error deleting data:', error);
                        showNotification('Terjadi kesalahan saat menghapus data.', 'error');
                    } finally {
                        hideLoader();
                    }

                    // Remove event listeners
                    confirmBtn.removeEventListener('click', executeDelete);
                    cancelBtn.removeEventListener('click', handleCancel);
                };

                const handleCancel = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');

                    // Remove event listeners
                    confirmBtn.removeEventListener('click', executeDelete);
                    cancelBtn.removeEventListener('click', handleCancel);
                };

                // Add event listeners
                confirmBtn.addEventListener('click', executeDelete);
                cancelBtn.addEventListener('click', handleCancel);

                // Close modal when clicking outside
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        handleCancel();
                    }
                });
            }

            // Function for single delete
            window.deleteTph = async function(id) {
                handleDelete(id, false);
            }

            // Function for batch delete
            window.batchDeleteTph = async function() {
                const selectedCheckboxes = document.querySelectorAll('.tph-checkbox:checked');
                const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);

                if (selectedIds.length === 0) return;

                handleDelete(selectedIds, true);
            }



            // Modifikasi event listeners yang sudah ada untuk memanggil updateTable
            regionalSelect.addEventListener('change', async function() {
                const regionalId = this.value;
                if (regionalId) {
                    showLoader();
                    try {
                        await populateWilayah(regionalId);
                        await updateTable();
                    } finally {
                        hideLoader();
                    }
                } else {
                    await updateTable();
                }
            });

            // Lakukan hal yang sama untuk event listener lainnya
            wilayahSelect.addEventListener('change', async function() {
                const wilayahId = this.value;
                if (wilayahId) {
                    showLoader();
                    try {
                        await populateEstate(wilayahId);
                        await updateTable();
                    } finally {
                        hideLoader();
                    }
                } else {
                    await updateTable();
                }
            });

            // Dan seterusnya untuk estate, afdeling, dan blok...

            // Panggil updateTable saat halaman dimuat pertama kali
            populateRegional();
            // updateTable();


            // tambah tph 
            blokSelect.addEventListener('change', function() {
                const btnAddTph = document.getElementById('btnAddTph');
                btnAddTph.disabled = !this.value;
            });

            // Fungsi untuk menampilkan modal
            window.showAddTphModal = function() {
                const modal = document.getElementById('addTphModal');
                const regionalText = regionalSelect.options[regionalSelect.selectedIndex].text;
                const estateText = estateSelect.options[estateSelect.selectedIndex].text;
                const afdelingText = afdelingSelect.options[afdelingSelect.selectedIndex].text;
                const blokText = blokSelect.options[blokSelect.selectedIndex].text;

                // Set nilai ke input modal
                document.getElementById('modalRegional').value = regionalText;
                document.getElementById('modalEstate').value = estateText;
                document.getElementById('modalAfdeling').value = afdelingText;
                document.getElementById('modalBlok').value = blokText;
                document.getElementById('modalTahunTanam').value = '';
                document.getElementById('modalNomorAncak').value = '';
                document.getElementById('modalNomorTph').value = '';

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            // Fungsi untuk menyembunyikan modal
            window.hideAddTphModal = function() {
                const modal = document.getElementById('addTphModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            // Fungsi untuk menyimpan TPH
            window.saveTph = async function() {
                const nomorTph = document.getElementById('modalNomorTph').value;
                const Tahuntanam = document.getElementById('modalTahunTanam').value;
                const NomorAncak = document.getElementById('modalNomorAncak').value;

                if (!nomorTph) {
                    showNotification('Nomor TPH harus diisi', 'error');
                    return;
                }

                if (!Tahuntanam) {
                    showNotification('Tahun Tanam Harus di isi', 'error');
                    return;
                }

                if (!NomorAncak) {
                    showNotification('Nomor Ancak harus diisi', 'error');
                    return;
                }

                showLoader();
                try {
                    const response = await fetch(`{{ route('tph-management.store') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            regional_id: regionalSelect.value,
                            estate_id: estateSelect.value,
                            afdeling_id: afdelingSelect.value,
                            blok_id: blokSelect.value,
                            tahun_tanam: Tahuntanam,
                            nomor_ancak: NomorAncak,
                            nomor_tph: nomorTph
                        })
                    });

                    if (!response.ok) throw new Error('Network response was not ok');
                    const result = await response.json();

                    if (result.success) {
                        showNotification('TPH berhasil ditambahkan', 'success');
                        hideAddTphModal();
                        updateTable(); // Refresh tabel
                    } else {
                        throw new Error(result.message || 'Gagal menambahkan TPH');
                    }
                } catch (error) {
                    // console.error('Error saving TPH:', error);
                    showNotification(`TPH sudah ada di database`);
                } finally {
                    hideLoader();
                }
            }

            // Click outside modal to close
            document.getElementById('addTphModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    hideAddTphModal();
                }
            });

        });
    </script>
</x-layouts.app>