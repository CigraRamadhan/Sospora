<x:layouts.app>
    <x-slot:title>Beranda</x-slot:title>

    {{-- ===== HERO SECTION ===== --}}
    <section class="relative min-h-[85vh] flex items-center overflow-hidden bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800">
        {{-- Background decorative elements --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-primary-600/20 rounded-full -translate-y-1/2 translate-x-1/3 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-500/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-3xl"></div>
            <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 32px 32px;"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 text-center">
            {{-- Text Content --}}
            <div class="text-white">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-sm font-medium text-white/90 mb-6">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Sistem Resmi Dispora Kota Bandung
                    </div>
                    <h1 class="font-heading text-4xl md:text-5xl xl:text-6xl font-extrabold leading-tight tracking-tight mb-6">
                        Booking Sarana <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-emerald-300">
                            Olahraga Online
                        </span>
                    </h1>
                    <p class="text-lg md:text-xl text-white/75 leading-relaxed max-w-2xl mx-auto mb-8">
                        Pesan lapangan olahraga milik Dinas Pemuda dan Olahraga Kota Bandung dengan mudah, cepat, dan transparan — langsung dari smartphone Anda.
                    </p>

                    {{-- Search Bar --}}
                    <form action="{{ route('sarana.index') }}" method="GET" class="flex gap-2 max-w-lg mx-auto">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                name="search"
                                placeholder="Cari lapangan, GOR, kolam renang..."
                                class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white text-slate-800 placeholder-slate-400 text-sm font-medium shadow-xl focus:outline-none focus:ring-2 focus:ring-primary-400"
                            >
                        </div>
                        <button type="submit" class="px-6 py-4 rounded-2xl bg-primary-500 hover:bg-primary-400 text-white font-semibold text-sm shadow-xl shadow-primary-500/30 transition hover:scale-105 active:scale-95 whitespace-nowrap">
                            Cari Sekarang
                        </button>
                    </form>

                    {{-- Stats --}}
                    <div class="flex items-center justify-center gap-6 mt-10 flex-wrap">
                        <div class="text-center">
                            <p class="font-heading font-extrabold text-2xl text-white">{{ $saranaPopuler->count() }}+</p>
                            <p class="text-xs text-white/60 mt-0.5">Fasilitas Olahraga</p>
                        </div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div class="text-center">
                            <p class="font-heading font-extrabold text-2xl text-white">{{ $kategoris->count() }}</p>
                            <p class="text-xs text-white/60 mt-0.5">Kategori Olahraga</p>
                        </div>
                        <div class="w-px h-10 bg-white/20"></div>
                        <div class="text-center">
                            <p class="font-heading font-extrabold text-2xl text-white">24/7</p>
                            <p class="text-xs text-white/60 mt-0.5">Booking Online</p>
                        </div>
                    </div>
                </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 text-white/40 animate-bounce">
            <span class="text-xs">Scroll</span>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </div>
    </section>

    {{-- ===== KATEGORI SECTION ===== --}}
    <section class="py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-primary-600 font-semibold text-sm tracking-widest uppercase mb-2">Fasilitas Kami</p>
                <h2 class="font-heading font-extrabold text-3xl md:text-4xl text-slate-900">Pilih Kategori Olahraga</h2>
                <p class="mt-3 text-slate-500 max-w-xl mx-auto">Tersedia berbagai fasilitas olahraga berstandar tinggi untuk mendukung aktivitas Anda.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($kategoris as $kategori)
                <a href="{{ route('sarana.index', ['kategori' => $kategori->id]) }}"
                   class="group flex flex-col items-center gap-3 p-5 rounded-2xl border border-slate-100 hover:border-primary-200 hover:bg-primary-50/50 transition duration-200 hover:-translate-y-1 text-center">
                    <div>
                        <p class="font-semibold text-sm text-slate-800 group-hover:text-primary-700">{{ $kategori->nama_kategori }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $kategori->saranas_count ?? 0 }} sarana</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== SARANA POPULER SECTION ===== --}}
    <section class="py-16 md:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                <div>
                    <p class="text-primary-600 font-semibold text-sm tracking-widest uppercase mb-2">Sarana Tersedia</p>
                    <h2 class="font-heading font-extrabold text-3xl md:text-4xl text-slate-900">Fasilitas Olahraga Populer</h2>
                </div>
                <a href="{{ route('sarana.index') }}" class="inline-flex items-center gap-2 text-primary-600 font-semibold text-sm hover:text-primary-800 shrink-0">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            @if($saranaPopuler->isEmpty())
                <x-empty-state icon="trophy" title="Belum Ada Sarana" message="Sarana olahraga akan segera ditambahkan oleh pengelola." />
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($saranaPopuler as $sarana)
                        <x-sarana-card :sarana="$sarana" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ===== HOW IT WORKS SECTION ===== --}}
    <section class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <p class="text-primary-600 font-semibold text-sm tracking-widest uppercase mb-2">Cara Kerja</p>
                <h2 class="font-heading font-extrabold text-3xl md:text-4xl text-slate-900">Cara Booking Sarana di SOSPORA</h2>
                <p class="mt-3 text-slate-500 max-w-xl mx-auto">Proses pemesanan yang mudah dan transparan dalam 4 langkah sederhana.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                @php
                $steps = [
                    ['num' => '01', 'icon' => 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z', 'title' => 'Cari Sarana', 'desc' => 'Pilih kategori olahraga dan temukan fasilitas yang sesuai kebutuhan Anda.'],
                    ['num' => '02', 'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z', 'title' => 'Pilih Jadwal', 'desc' => 'Lihat kalender ketersediaan slot secara real-time dan pilih waktu yang cocok.'],
                    ['num' => '03', 'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z', 'title' => 'Bayar', 'desc' => 'Transfer ke rekening Dispora atau bayar langsung di lokasi saat penggunaan.'],
                    ['num' => '04', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Konfirmasi & Gunakan', 'desc' => 'Admin Dispora memverifikasi pembayaran dan Anda mendapatkan konfirmasi resmi.'],
                ];
                @endphp

                @foreach($steps as $step)
                <div class="relative flex flex-col items-center text-center group">
                    {{-- Connector line --}}
                    @if(!$loop->last)
                    <div class="hidden md:block absolute top-8 left-1/2 w-full h-0.5 bg-gradient-to-r from-primary-200 to-slate-100 z-0" style="left: calc(50% + 2.5rem)"></div>
                    @endif

                    <div class="relative z-10 w-16 h-16 rounded-2xl bg-primary-50 border-2 border-primary-100 group-hover:border-primary-400 group-hover:bg-primary-100 flex items-center justify-center mb-4 transition duration-200">
                        <svg class="w-7 h-7 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}" />
                        </svg>
                        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-primary-600 text-white text-[10px] font-bold flex items-center justify-center">{{ $step['num'] }}</span>
                    </div>
                    <h3 class="font-heading font-bold text-base text-slate-900 mb-2">{{ $step['title'] }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== CTA SECTION ===== --}}
    <section class="py-16 md:py-20 bg-gradient-to-r from-primary-800 to-primary-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-heading font-extrabold text-3xl md:text-4xl text-white mb-4">Siap Berolahraga Hari Ini?</h2>
            <p class="text-white/75 text-lg mb-8">Daftar akun gratis dan mulai booking sarana olahraga Dispora Kota Bandung sekarang.</p>
            <div class="flex items-center justify-center gap-4 flex-wrap">
                <a href="{{ route('sarana.index') }}"
                   class="px-8 py-4 rounded-2xl bg-white text-primary-700 font-bold text-sm hover:bg-slate-100 transition shadow-lg shadow-black/10 hover:scale-105 active:scale-95">
                    Lihat Semua Sarana
                </a>
                @guest
                <a href="{{ route('register') }}"
                   class="px-8 py-4 rounded-2xl bg-primary-500 text-white font-bold text-sm hover:bg-primary-400 border border-white/20 transition shadow-lg hover:scale-105 active:scale-95">
                    Daftar Sekarang — Gratis
                </a>
                @endguest
            </div>
        </div>
    </section>
</x:layouts.app>
