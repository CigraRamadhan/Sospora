@props([
    'title' => 'Belum Ada Data',
    'description' => 'Tidak ada data yang dapat ditampilkan saat ini.',
    'actionUrl' => null,
    'actionText' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center text-center p-8 sm:p-12 rounded-2xl bg-white border border-dashed border-slate-200']) }}>
    <div class="w-16 h-16 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center mb-4">
        {{ $icon ?? '' }}
        @if(!isset($icon))
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
        @endif
    </div>

    <h3 class="text-base font-heading font-semibold text-slate-900">{{ $title }}</h3>
    <p class="mt-1 text-sm text-slate-500 max-w-sm">{{ $description }}</p>

    @if($actionUrl && $actionText)
        <a
            href="{{ $actionUrl }}"
            class="mt-5 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition shadow-sm"
        >
            {{ $actionText }}
        </a>
    @endif
</div>
