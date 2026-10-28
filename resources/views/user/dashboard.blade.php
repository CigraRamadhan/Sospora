<x:layouts.app>
    <x-slot:title>Dashboard Saya</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Greeting --}}
        <div class="mb-8">
            <h1 class="font-heading font-extrabold text-2xl md:text-3xl text-slate-900">
                Selamat datang, {{ Auth::user()->nama }}! 👋
            </h1>
            <p class="text-slate-500 mt-1 text-sm">Pantau status booking dan aktivitas olahraga Anda di sini.</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @php
            $statItems = [
                ['label' => 'Booking Aktif', 'value' => $stats['aktif'], 'color' => 'primary', 'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5'],
                ['label' => 'Total Booking', 'value' => $stats['total'], 'color' => 'slate', 'icon' => 'M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z'],
                ['label' => 'Booking Selesai', 'value' => $stats['selesai'], 'color' => 'emerald', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Notifikasi Baru', 'value' => $stats['notifikasi_belum_dibaca'], 'color' => 'amber', 'icon' => 'M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0'],
            ];
            @endphp

            @foreach($statItems as $stat)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-{{ $stat['color'] }}-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-{{ $stat['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
                        </svg>
                    </div>
                </div>
                <p class="font-heading font-extrabold text-2xl text-slate-900">{{ $stat['value'] }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('sarana.index') }}"
               class="group flex items-center gap-4 p-5 bg-gradient-to-br from-primary-600 to-primary-800 text-white rounded-2xl hover:from-primary-500 hover:to-primary-700 transition shadow-md shadow-primary-500/20 hover:scale-[1.02] active:scale-[0.98]">
                <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                </div>
                <div>
                    <p class="font-semibold text-sm">Cari Sarana</p>
                    <p class="text-xs text-white/70 mt-0.5">Booking fasilitas olahraga</p>
                </div>
            </a>

            <a href="{{ route('user.booking.index') }}"
               class="group flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 hover:border-primary-200 hover:bg-primary-50/30 transition hover:scale-[1.02] active:scale-[0.98]">
                <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008z" /></svg>
                </div>
                <div>
                    <p class="font-semibold text-sm text-slate-800">Booking Saya</p>
                    <p class="text-xs text-slate-500 mt-0.5">Lihat booking aktif</p>
                </div>
            </a>

            <a href="{{ route('user.notifikasi.index') }}"
               class="group flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 hover:border-primary-200 hover:bg-primary-50/30 transition hover:scale-[1.02] active:scale-[0.98]">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0 relative">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                    @if($stats['notifikasi_belum_dibaca'] > 0)
                    <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-red-600 text-white text-[10px] font-bold flex items-center justify-center">
                        {{ $stats['notifikasi_belum_dibaca'] > 9 ? '9+' : $stats['notifikasi_belum_dibaca'] }}
                    </span>
                    @endif
                </div>
                <div>
                    <p class="font-semibold text-sm text-slate-800">Notifikasi</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $stats['notifikasi_belum_dibaca'] }} belum dibaca</p>
                </div>
            </a>
        </div>

        {{-- Recent Bookings --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h2 class="font-heading font-bold text-slate-900">Booking Terbaru</h2>
                <a href="{{ route('user.booking.index') }}" class="text-xs text-primary-600 font-semibold hover:text-primary-800">Lihat Semua →</a>
            </div>

            @if($bookingTerbaru->isEmpty())
            <div class="py-12">
                <x-empty-state icon="calendar" title="Belum Ada Booking" message="Anda belum pernah melakukan booking. Mulai cari sarana olahraga!" :action-href="route('sarana.index')" action-label="Cari Sarana Sekarang" />
            </div>
            @else
            <div class="divide-y divide-slate-50">
                @foreach($bookingTerbaru as $booking)
                <div class="flex items-center gap-4 px-5 py-4 hover:bg-slate-50/50 transition">
                    {{-- Sarana Photo --}}
                    <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-slate-100">
                        @if($booking->sarana->foto_utama)
                        <img src="{{ $booking->sarana->foto_utama }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-xl">
                            {{ $booking->sarana->kategori->icon ?? '🏟️' }}
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-slate-800 truncate">{{ $booking->sarana->nama_sarana }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ \Carbon\Carbon::parse($booking->tanggal)->isoFormat('D MMM Y') }} • {{ substr($booking->slot_mulai, 0, 5) }}–{{ substr($booking->slot_selesai, 0, 5) }}
                        </p>
                        <p class="text-xs font-mono text-slate-400 mt-0.5">{{ $booking->kode_booking }}</p>
                    </div>
                    <x-status-badge :status="$booking->status" />
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</x:layouts.app>
