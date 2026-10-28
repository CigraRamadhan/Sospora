@props(['sarana'])

<div {{ $attributes->merge(['class' => 'group flex flex-col rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-primary-200 transition-all duration-300 overflow-hidden']) }}>
    <!-- Image Preview -->
    <div class="relative w-full h-48 sm:h-52 bg-slate-100 overflow-hidden">
        @if($sarana->foto_utama)
            <img
                src="{{ $sarana->foto_utama }}"
                alt="{{ $sarana->nama_sarana }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                loading="lazy"
            >
        @else
            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-primary-50 via-blue-50 to-slate-100 text-primary-400">
                <svg class="w-12 h-12 stroke-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.75c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                </svg>
                <span class="text-xs text-slate-400 mt-2 font-medium">SOSPORA Bandung</span>
            </div>
        @endif

        <!-- Category Badge -->
        <div class="absolute top-3 left-3">
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-white/95 text-primary-700 shadow-sm backdrop-blur-md border border-white/40">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-600"></span>
                {{ $sarana->kategori->nama_kategori ?? 'Sarana Olahraga' }}
            </span>
        </div>

        <!-- Status Badge (if nonaktif) -->
        @if($sarana->status !== 'aktif')
            <div class="absolute top-3 right-3">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-600 text-white shadow-md">
                    Maintenance
                </span>
            </div>
        @endif
    </div>

    <!-- Content -->
    <div class="flex-1 flex flex-col p-5">
        <h3 class="text-lg font-heading font-semibold text-slate-900 group-hover:text-primary-600 transition-colors line-clamp-1">
            <a href="{{ route('sarana.show', $sarana->slug) }}">
                {{ $sarana->nama_sarana }}
            </a>
        </h3>

        <!-- Location -->
        <div class="mt-2 flex items-center gap-1.5 text-xs text-slate-500 line-clamp-1">
            <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
            </svg>
            <span>{{ $sarana->lokasi }}</span>
        </div>

        <!-- Operating Hours & Interval -->
        <div class="mt-3 flex items-center gap-4 text-xs text-slate-600 py-2 border-y border-slate-100">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ substr($sarana->jam_buka, 0, 5) }} - {{ substr($sarana->jam_tutup, 0, 5) }} WIB</span>
            </div>
            <div class="text-slate-300">•</div>
            <div>
                <span>Per {{ $sarana->interval_slot_menit }} Menit</span>
            </div>
        </div>

        <!-- Price and CTA -->
        <div class="mt-auto pt-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] uppercase tracking-wider font-semibold text-slate-400 block">Tarif Retribusi</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-base sm:text-lg font-heading font-bold text-primary-700">
                        Rp {{ number_format($sarana->harga_per_slot, 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-slate-400 font-normal">/ slot</span>
                </div>
            </div>

            <a
                href="{{ route('sarana.show', $sarana->slug) }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary-50 text-primary-700 font-medium text-xs hover:bg-primary-600 hover:text-white transition-all duration-200"
            >
                <span>Lihat Detail</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </a>
        </div>
    </div>
</div>
