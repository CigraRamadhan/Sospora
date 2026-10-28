<x:layouts.app>
    <x-slot:title>Katalog Sarana</x-slot:title>

    {{-- Page Header --}}
    <div class="bg-gradient-to-br from-primary-900 to-primary-700 text-white py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-heading font-extrabold text-3xl md:text-4xl mb-2">Katalog Sarana Olahraga</h1>
            <p class="text-white/70 text-base">Temukan dan pesan fasilitas olahraga Dispora Kota Bandung</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="lg:grid lg:grid-cols-[280px_1fr] gap-8">

            {{-- ===== SIDEBAR FILTER ===== --}}
            <aside x-data="{ open: false }" class="mb-6 lg:mb-0">
                {{-- Mobile filter toggle --}}
                <button
                    type="button"
                    x-on:click="open = !open"
                    class="lg:hidden w-full flex items-center justify-between px-4 py-3 rounded-xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 shadow-sm mb-4"
                >
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" /></svg>
                        Filter
                    </span>
                    <svg x-bind:class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>

                <div x-show="open" x-cloak class="lg:block">
                    <div class="lg:block bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                        <form action="{{ route('sarana.index') }}" method="GET" id="filter-form">
                            <h3 class="font-heading font-bold text-slate-900 mb-4">Filter Sarana</h3>

                            {{-- Search --}}
                            <div class="mb-5">
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Kata Kunci</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                                    </div>
                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="Nama atau lokasi..."
                                        x-data
                                        x-on:input.debounce.500ms="$el.closest('form').submit()"
                                        class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent"
                                    >
                                </div>
                            </div>

                            {{-- Kategori --}}
                            <div class="mb-5">
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Kategori</label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="kategori" value="" {{ !request('kategori') ? 'checked' : '' }} onchange="this.form.submit()" class="text-primary-600">
                                        <span class="text-sm text-slate-700 group-hover:text-primary-600 font-medium">Semua Kategori</span>
                                    </label>
                                    @foreach($kategoris as $kat)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="kategori" value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'checked' : '' }} onchange="this.form.submit()" class="text-primary-600">
                                        <span class="text-sm text-slate-700 group-hover:text-primary-600">{{ $kat->icon ?? '' }} {{ $kat->nama_kategori }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Harga max --}}
                            <div class="mb-5">
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Harga Maks / Slot</label>
                                <select name="harga_max" onchange="this.form.submit()" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                                    <option value="">Semua Harga</option>
                                    <option value="50000" {{ request('harga_max') == '50000' ? 'selected' : '' }}>s.d. Rp 50.000</option>
                                    <option value="100000" {{ request('harga_max') == '100000' ? 'selected' : '' }}>s.d. Rp 100.000</option>
                                    <option value="150000" {{ request('harga_max') == '150000' ? 'selected' : '' }}>s.d. Rp 150.000</option>
                                    <option value="200000" {{ request('harga_max') == '200000' ? 'selected' : '' }}>s.d. Rp 200.000</option>
                                </select>
                            </div>

                            @if(request()->hasAny(['search', 'kategori', 'harga_max']))
                            <a href="{{ route('sarana.index') }}" class="block w-full text-center py-2 rounded-xl text-sm text-red-600 font-medium hover:bg-red-50 transition">
                                Hapus Filter
                            </a>
                            @endif
                        </form>
                    </div>
                </div>

                {{-- Desktop always show --}}
                <div class="hidden lg:block bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <form action="{{ route('sarana.index') }}" method="GET">
                        <h3 class="font-heading font-bold text-slate-900 mb-4">Filter Sarana</h3>
                        <div class="mb-5">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Kata Kunci</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau lokasi..." x-data x-on:input.debounce.500ms="$el.closest('form').submit()" class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Kategori</label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="kategori" value="" {{ !request('kategori') ? 'checked' : '' }} onchange="this.form.submit()" class="text-primary-600">
                                    <span class="text-sm text-slate-700 group-hover:text-primary-600 font-medium">Semua Kategori</span>
                                </label>
                                @foreach($kategoris as $kat)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="kategori" value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'checked' : '' }} onchange="this.form.submit()" class="text-primary-600">
                                    <span class="text-sm text-slate-700 group-hover:text-primary-600">{{ $kat->nama_kategori }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Harga Maks / Slot</label>
                            <select name="harga_max" onchange="this.form.submit()" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                                <option value="">Semua Harga</option>
                                <option value="50000" {{ request('harga_max') == '50000' ? 'selected' : '' }}>s.d. Rp 50.000</option>
                                <option value="100000" {{ request('harga_max') == '100000' ? 'selected' : '' }}>s.d. Rp 100.000</option>
                                <option value="150000" {{ request('harga_max') == '150000' ? 'selected' : '' }}>s.d. Rp 150.000</option>
                                <option value="200000" {{ request('harga_max') == '200000' ? 'selected' : '' }}>s.d. Rp 200.000</option>
                            </select>
                        </div>
                        @if(request()->hasAny(['search', 'kategori', 'harga_max']))
                        <a href="{{ route('sarana.index') }}" class="block w-full text-center py-2 rounded-xl text-sm text-red-600 font-medium hover:bg-red-50 transition">Hapus Filter</a>
                        @endif
                    </form>
                </div>
            </aside>

            {{-- ===== MAIN CONTENT ===== --}}
            <div>
                {{-- Results Header --}}
                <div class="flex items-center justify-between mb-6">
                    <p class="text-sm text-slate-500">
                        Menampilkan <span class="font-semibold text-slate-800">{{ $sarana->total() }}</span> sarana
                        @if(request('search')) untuk "<em class="text-primary-600">{{ request('search') }}</em>"@endif
                    </p>
                </div>

                @if($sarana->isEmpty())
                    <x-empty-state
                        icon="magnifying-glass"
                        title="Sarana Tidak Ditemukan"
                        message="Tidak ada sarana yang cocok dengan filter Anda. Coba ubah filter atau hapus kata kunci pencarian."
                        :action-href="route('sarana.index')"
                        action-label="Tampilkan Semua"
                    />
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                        @foreach($sarana as $item)
                            <x-sarana-card :sarana="$item" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($sarana->hasPages())
                    <div class="mt-8">
                        {{ $sarana->links() }}
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x:layouts.app>
