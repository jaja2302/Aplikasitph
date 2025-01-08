@props([
'text' => 'Loading...',
'size' => 'w-16 h-16',
])

<!-- Page Loader -->
<div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm transition-all"
    x-data="{ loading: false }"
    x-show="loading"
    x-on:show-loader.window="loading = true"
    x-on:hide-loader.window="loading = false"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    style="display: none;">
    <div class="text-center">
        <div class="inline-block animate-spin rounded-full border-4 border-primary-200 border-t-primary-600 {{ $size }}"></div>
        <p class="mt-4 text-xl font-semibold text-white">{{ $text }}</p>
    </div>
</div>