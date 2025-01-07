<div class="h-screen bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg p-4">
        <div class="container mx-auto">
            <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
        </div>
    </nav>

    <!-- select regional -->
    <div class="container mx-auto p-4">
        <select wire:model.live="selectedRegional" class="form-select w-full">
            <option value="">-- Pilih Regional --</option>
            @foreach ($regional as $item)
            <option value="{{ $item->id }}">{{ $item->nama }}</option>
            @endforeach
        </select>
    </div>
    <!-- select wilayah based regional -->
    <div class="container mx-auto p-4">
        <select wire:model.live="selectedWilayah" class="form-select w-full" @if(!$wilayah) disabled @endif>
            <option value="">-- Pilih Wilayah --</option>
            @foreach ($wilayah ?? [] as $item)
            <option value="{{ $item->id }}">{{ $item->nama }}</option>
            @endforeach
        </select>
    </div>
    <!-- select estate based wilayah -->
    <div class="container mx-auto p-4">
        <select wire:model.live="selectedEstate" class="form-select w-full" @if(!$estate) disabled @endif>
            <option value="">-- Pilih Estate --</option>
            @foreach ($estate ?? [] as $item)
            <option value="{{ $item->id }}">{{ $item->nama }}</option>
            @endforeach
        </select>
    </div>
    <!-- select afdeling based estate -->
    <div class="container mx-auto p-4">
        <select wire:model.live="selectedAfdeling" class="form-select w-full" @if(!$afdeling) disabled @endif>
            <option value="">-- Pilih Afdeling --</option>
            @foreach ($afdeling ?? [] as $item)
            <option value="{{ $item->id }}">{{ $item->nama }}</option>
            @endforeach
        </select>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow-md">
            <!-- Map Container -->
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
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map
        var map = L.map('map').setView([-6.200000, 106.816666], 12); // Jakarta coordinates

        // Add tile layer (you can change the style)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
    });
</script>
@endpush