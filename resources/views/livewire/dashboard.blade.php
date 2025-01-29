<div class="space-y-6" x-data="{ open: false }">
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
                            {{ $title }}
                        </h2>
                        <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                Monitoring TPH @if($user) 'Bisa edit' @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Button -->
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <button
                    type="button"
                    @click="open = true"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Panduan
                </button>
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
                        @if(!$blok || $isProcessing || $focusOnTPHState) disabled @endif>
                        <option value="">Pilih Blok</option>
                        @foreach($blok as $blk)
                        <option value="{{ $blk->id }}">{{ $blk->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden" style="position: relative; z-index: 0;">
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

    <!-- Legend/Info Section -->
    @if($legendInfo)
    <!-- Legend Card -->
    @if($legendInfo['check_tph_per_blok'])
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="space-y-6">

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Klik Nomor TPH untuk melihat detail data TPH di peta</h3>
                <button
                    wire:click="resetMapView"
                    class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors">
                    Reset View
                </button>
            </div>
            @if($stateDetailEstateAfdeling)
            <!-- Basic Info Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Detail Estate {{ $estateName }} Afdeling {{ $afdelingName }}</h3>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-600">Total Blok</div>
                            <div class="text-2xl font-bold text-gray-900">
                                {{ $legendInfo['total_blok_name_count'] }}
                                <span>(<span class="text-green-500">{{ $legendInfo['verifiedblokcount'] }}</span>/<span class="text-red-500">{{ $legendInfo['unveridblokcount'] }}</span>)</span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="space-y-3">
                                <!-- Header -->
                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-gray-600">Total TPH</div>
                                    <span class="text-sm font-medium text-gray-900">{{ $legendInfo['progress_percentage'] }}%</span>
                                </div>

                                <!-- Total Number -->
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $legendInfo['total_tph'] }}
                                    <span>(<span class="text-green-500">{{ $legendInfo['total_tph_verif'] }}</span>/<span class="text-red-500">{{ $legendInfo['total_tph_unverif'] }}</span>)</span>
                                </div>



                                <!-- Progress Bar -->
                                <div class="relative">
                                    <div class="h-1.5 rounded-full bg-gray-200">
                                        <div class="h-1.5 rounded-full bg-green-500 transition-all duration-300"
                                            style="width: {{ $legendInfo['progress_percentage'] }}%">
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


            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Detail Blok per Titik TPH</h3>
            </div>
            <!-- Blok Status Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 overflow-y-auto max-h-[600px]">
                <!-- Blok Terinput -->
                <div class="bg-green-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-green-900 mb-3">Blok Terinput</h4>
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($legendInfo['tph_detail_per_blok'] as $tphDetail)
                        @if($tphDetail->verified_tph > 0)
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $tphDetail->blok_kode }} ({{ $tphDetail->verified_tph }}/{{ $tphDetail->total_tph }})
                            </span>
                            @if($tphDetail->verified_tph_numbers)
                            <div class="text-xs text-gray-600 break-words">
                                <span class="font-medium">TPH:</span>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(collect(explode(',', $tphDetail->verified_tph_numbers))->sort() as $tphNumber)
                                    <button
                                        wire:click="focusOnTPH('{{ $tphDetail->blok_kode }}', {{ $tphNumber }})"
                                        class="px-1.5 py-0.5 bg-green-50 rounded hover:bg-green-100 transition-colors cursor-pointer">
                                        {{ $tphNumber }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>

                <!-- Blok Belum Terinput -->
                <div class="bg-red-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-red-900 mb-3">Blok Belum Terinput</h4>
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($legendInfo['tph_detail_per_blok'] as $tphDetail)
                        @if($tphDetail->unverified_tph > 0)
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $tphDetail->blok_kode }} ({{ $tphDetail->unverified_tph }}/{{ $tphDetail->total_tph }})
                            </span>
                            @if($tphDetail->unverified_tph_numbers)
                            <div class="text-xs text-gray-600 break-words">
                                <span class="font-medium">TPH:</span>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(collect(explode(',', $tphDetail->unverified_tph_numbers))->sort() as $tphNumber)
                                    <span class="px-1.5 py-0.5 bg-red-50 rounded">{{ $tphNumber }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="bg-white shadow-sm rounded-lg p-6">
        Tidak ada data TPH bisa ditampilkan
    </div>
    @endif
    @endif

    <!-- Existing Modals -->
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
    <div class="relative">
        <!-- Modal Overlay -->
        <div x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 z-40"
            @click="open = false">
        </div>

        <!-- Guide Content Modal -->
        <div x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4"
            class="fixed inset-4 sm:inset-auto sm:right-8 sm:bottom-8 sm:top-auto sm:left-auto sm:w-[600px] bg-white rounded-xl shadow-2xl z-50 max-h-[80vh] overflow-y-auto">

            <!-- Guide Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800">Panduan Penggunaan</h2>
                <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Guide Content -->
            <div class="p-6 space-y-6">
                <div class="space-y-4 text-gray-600">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">🎯 Cara Memulai:</h3>
                        <ol class="list-decimal list-inside space-y-2 ml-2">
                            <li>Pilih Regional, Wilayah, Estate, dan Afdeling secara berurutan pada panel filter</li>
                            <li>Anda dapat memilih Blok spesifik untuk melihat detail TPH pada blok tersebut</li>
                        </ol>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">🗺️ Fitur Peta:</h3>
                        <ul class="list-disc list-inside space-y-1 ml-2">
                            <li>Gunakan slider Opacity untuk mengatur transparansi layer blok</li>
                            <li>Klik pada titik TPH untuk melihat detail informasi</li>
                            <li>Pilih antara tampilan Satellite atau OpenStreetMap di panel kanan atas</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">📊 Detail Data TPH:</h3>
                        <ul class="list-disc list-inside space-y-1 ml-2">
                            <li>Tombol Reset View mengembalikan tampilan ke seluruh area afdeling setelah memilih Nomor tph</li>
                            <li>Titik Hijau: TPH terverifikasi</li>
                            <li>Titik Merah: TPH belum terverifikasi</li>
                            <li>Blok Biru: Blok yang sudah memiliki TPH terverifikasi</li>
                            <li>Blok Merah: Blok yang belum memiliki TPH terverifikasi</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">🔍 Detail TPH:</h3>
                        <ul class="list-disc list-inside space-y-1 ml-2">
                            <li>Panel detail menampilkan status verifikasi per blok</li>
                            <li>Klik nomor TPH pada panel untuk menemukan lokasi TPH spesifik</li>
                            <li>Persentase progress menunjukkan kemajuan verifikasi TPH</li>
                        </ul>
                    </div>

                    @if($user)
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">✏️ Fitur Edit (Admin):</h3>
                        <ul class="list-disc list-inside space-y-1 ml-2">
                            <li>Klik titik TPH untuk membuka popup informasi</li>
                            <li>Gunakan tombol Edit TPH untuk mengubah nomor TPH dan ancak</li>
                            <li>Perubahan akan langsung tersimpan setelah dikonfirmasi</li>
                        </ul>
                    </div>
                    @endif
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

        // Add default layer
        satelliteLayer.addTo(map);

        // Initialize layer control from the select element
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

        // Initialize opacity control from the range input
        const opacitySlider = document.getElementById('mapOpacity');
        const opacityValue = document.getElementById('opacityValue');

        opacitySlider.addEventListener('input', (e) => {
            const value = e.target.value;
            opacityValue.textContent = value + '%';
            currentFillOpacity = value / 100;

            if (plotLayer) {
                plotLayer.eachLayer(function(layer) {
                    layer.setStyle({
                        fillOpacity: currentFillOpacity
                    });
                });
            }
        });

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
                            TPH: ${feature.properties.tph}<br>
                            Estate: ${feature.properties.estate}<br>
                            Afdeling: ${feature.properties.afdeling}<br>
                            User Input: ${feature.properties.user_input}<br>
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

        // Add this inside your existing script tag, within the Livewire initialized event listener
        Livewire.on('focus-tph', ({
            coordinates
        }) => {
            // Clear any existing highlight markers
            if (window.highlightMarker) {
                map.removeLayer(window.highlightMarker);
            }

            // Create a new highlight marker
            window.highlightMarker = L.circleMarker([coordinates.lat, coordinates.lon], {
                radius: 12,
                fillColor: '#FFD700',
                color: '#000',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            });
            //             TPH Info
            // Blok: C007A
            // Ancak: 42
            // TPH: 14
            // Estate: NBE
            // Afdeling: AFD-OA
            // Update popup content untuk highlight marker
            window.highlightMarker.bindPopup(
                `<b>TPH Info</b>
                <br>Blok: ${coordinates.blok}
                <br>TPH: ${coordinates.tph}
                <br>Estate: ${coordinates.estate}
                <br>Afdeling: ${coordinates.afdeling}
                <br>User Input: ${coordinates.user_input}
                ${@this.user ? `
                    <br><br>
                    <button 
                        wire:click="editTPH(${coordinates.id})"
                        style="background: #4CAF50; color: white; padding: 4px 8px; 
                        border: none; border-radius: 4px; cursor: pointer; margin-top: 8px;">
                        Edit TPH
                    </button>
                ` : ''}`
            ).openPopup();

            // Add marker to map
            window.highlightMarker.addTo(map);

            // Center map on the TPH location with zoom
            map.setView([coordinates.lat, coordinates.lon], 18);
        });

        // Tambahkan di dalam script yang sudah ada
        Livewire.on('reset-map-view', ({
            plotMap,
            coordinatesTPH
        }) => {
            // Remove highlight marker if exists
            if (window.highlightMarker) {
                map.removeLayer(window.highlightMarker);
            }

            // Reset the map view to show all plots and TPH points
            if (plotLayer && tphLayer) {
                // Create a feature group containing both layers
                const allLayers = L.featureGroup([plotLayer, tphLayer]);

                // Fit the map to show all features
                map.fitBounds(allLayers.getBounds(), {
                    padding: [50, 50],
                    maxZoom: 16 // Limit max zoom to keep context
                });
            } else if (plotLayer) {
                map.fitBounds(plotLayer.getBounds(), {
                    padding: [50, 50],
                    maxZoom: 16
                });
            } else if (tphLayer) {
                map.fitBounds(tphLayer.getBounds(), {
                    padding: [50, 50],
                    maxZoom: 16
                });
            }

            // Reset any active filters or highlights
            if (plotLayer) {
                plotLayer.eachLayer(function(layer) {
                    layer.setStyle({
                        weight: 2,
                        color: 'white',
                        dashArray: '3',
                        fillOpacity: currentFillOpacity
                    });
                });
            }

            // Make sure all TPH points are visible
            if (tphLayer) {
                tphLayer.eachLayer(function(layer) {
                    layer.setStyle({
                        radius: 8,
                        weight: 1,
                        opacity: 1,
                        fillOpacity: 0.8
                    });
                });
            }
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