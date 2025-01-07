@props([
'text' => 'Loading...',
'size' => 'w-16 h-16',
])

<div class="loader fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900">
    <div class="text-center">
        <div class="loader-spin inline-block {{ $size }} border-4 border-primary-200 border-t-primary-600 rounded-full"></div>
        <h2 class="mt-4 text-xl font-semibold text-white">{{ $text }}</h2>
    </div>
</div>