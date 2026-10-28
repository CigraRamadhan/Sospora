<x:layouts.app>
    <x-slot:title>{{ $sarana->nama_sarana }}</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-primary-600 transition">Beranda</a>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <a href="{{ route('sarana.index') }}" class="hover:text-primary-600 transition">Katalog</a>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <span class="text-slate-800 font-medium truncate">{{ $sarana->nama_sarana }}</span>
        </nav>

        <div class="lg:grid lg:grid-cols-[1fr_380px] gap-8">

            {{-- ===== LEFT: Gallery & Info ===== --}}
            <div>
                {{-- Photo Gallery --}}
                @if($sarana->fotos->isNotEmpty())
                <div x-data="{ active: 0 }" class="rounded-2xl overflow-hidden bg-slate-900 mb-6 relative">
                    <div class="relative h-64 md:h-80 xl:h-96 overflow-hidden">
                        @foreach($sarana->fotos as $i => $foto)
                        <img
                            x-show="active === {{ $i }}"
                            src="{{ asset('storage/' . $foto->path_file) }}"
                            alt="{{ $sarana->nama_sarana }}"
                            class="w-full h-full object-cover transition duration-300"
                            style="{{ $i > 0 ? 'display:none' : '' }}"
                        >
                        @endforeach

                        {{-- Navigation arrows --}}
                        @if($sarana->fotos->count() > 1)
                        <button x-on:click="active = active > 0 ? active - 1 : {{ $sarana->fotos->count() - 1 }}" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/50 text-white flex items-center justify-center hover:bg-black/70 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                        </button>
                        <button x-on:click="active = active < {{ $sarana->fotos->count() - 1 }} ? active + 1 : 0" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/50 text-white flex items-center justify-center hover:bg-black/70 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        </button>
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                            @foreach($sarana->fotos as $i => $foto)
                            <button x-on:click="active = {{ $i }}" x-bind:class="active === {{ $i }} ? 'bg-white w-4' : 'bg-white/50'" class="h-1.5 rounded-full transition-all duration-200"></button>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Thumbnails --}}
                    @if($sarana->fotos->count() > 1)
                    <div class="flex gap-2 p-3 overflow-x-auto scrollbar-hide">
                        @foreach($sarana->fotos as $i => $foto)
                        <button x-on:click="active = {{ $i }}" x-bind:class="active === {{ $i }} ? 'ring-2 ring-primary-400 opacity-100' : 'opacity-60 hover:opacity-80'" class="flex-shrink-0 w-16 h-12 rounded-lg overflow-hidden transition">
                            <img src="{{ asset('storage/' . $foto->path_file) }}" class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
                @else
                <div class="rounded-2xl bg-slate-100 h-64 flex items-center justify-center mb-6">
                    <div class="text-center text-slate-400">
                        <svg class="w-16 h-16 mx-auto mb-2 opacity-40" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                        <p class="text-sm">Belum ada foto</p>
                    </div>
                </div>
                @endif

                {{-- Sarana Info --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm mb-6">
                    <div class="flex items-start justify-between flex-wrap gap-3 mb-4">
                        <div>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-primary-50 text-primary-700 border border-primary-100 mb-2">
                                {{ $sarana->kategori->nama_kategori }}
                            </span>
                            <h1 class="font-heading font-extrabold text-2xl md:text-3xl text-slate-900">{{ $sarana->nama_sarana }}</h1>
                        </div>
                        <div class="text-right">
                            <p class="font-heading font-extrabold text-2xl text-primary-700">Rp {{ number_format($sarana->harga_per_slot, 0, ',', '.') }}</p>
                            <p class="text-xs text-slate-400">per slot ({{ $sarana->interval_slot_menit }} menit)</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4 text-sm text-slate-600 border-t border-slate-100 pt-4">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            <span>{{ $sarana->lokasi }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ substr($sarana->jam_buka, 0, 5) }} – {{ substr($sarana->jam_tutup, 0, 5) }} WIB</span>
                        </div>
                    </div>

                    @if($sarana->deskripsi)
                    <div class="mt-4 text-sm text-slate-600 leading-relaxed prose prose-slate max-w-none">
                        <p>{{ $sarana->deskripsi }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ===== RIGHT: Slot Calendar ===== --}}
            <div>
                <div
                    x-data="{
                        tanggal: '{{ $tanggal }}',
                        slots: {{ json_encode($slots) }},
                        loading: false,
                        selectedSlot: null,
                        saranaId: {{ $sarana->id }},
                        async fetchSlots() {
                            this.loading = true;
                            this.selectedSlot = null;
                            try {
                                const res = await fetch(`/api/sarana/${this.saranaId}/ketersediaan?tanggal=${this.tanggal}`);
                                const data = await res.json();
                                this.slots = data.slots || [];
                            } catch(e) {
                                this.slots = [];
                            } finally {
                                this.loading = false;
                            }
                        },
                        selectSlot(slot) {
                            if (slot.status === 'tersedia') {
                                this.selectedSlot = slot;
                            }
                        }
                    }"
                    class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden sticky top-24"
                >
                    {{-- Header --}}
                    <div class="bg-gradient-to-br from-primary-800 to-primary-700 px-5 py-4 text-white">
                        <h2 class="font-heading font-bold text-base">Kalender Ketersediaan</h2>
                        <p class="text-xs text-white/70 mt-0.5">Pilih tanggal dan slot waktu</p>
                    </div>

                    <div class="p-5">
                        {{-- Date Picker --}}
                        <div class="mb-5">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Pilih Tanggal</label>
                            <input
                                type="date"
                                x-model="tanggal"
                                min="{{ now()->format('Y-m-d') }}"
                                max="{{ now()->addMonths(2)->format('Y-m-d') }}"
                                x-on:change="fetchSlots()"
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent cursor-pointer"
                            >
                        </div>

                        {{-- Legend --}}
                        <div class="flex items-center gap-4 text-xs text-slate-500 mb-4">
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-400"></span> Tersedia</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-red-400"></span> Terisi</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-slate-300"></span> N/A</span>
                        </div>

                        {{-- Slot Grid --}}
                        <div class="min-h-[200px]">
                            <div x-show="loading" class="flex items-center justify-center py-12">
                                <svg class="animate-spin w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>

                            <div x-show="!loading && slots.length === 0" class="text-center py-10 text-slate-400">
                                <p class="text-sm">Tidak ada slot tersedia untuk tanggal ini.</p>
                            </div>

                            <div x-show="!loading && slots.length > 0" class="grid grid-cols-2 gap-2">
                                <template x-for="(slot, i) in slots" :key="i">
                                    <button
                                        type="button"
                                        x-on:click="selectSlot(slot)"
                                        x-bind:disabled="slot.status !== 'tersedia'"
                                        x-bind:class="{
                                            'bg-emerald-50 border-emerald-300 text-emerald-800 hover:bg-emerald-100 hover:border-emerald-400 cursor-pointer': slot.status === 'tersedia' && selectedSlot?.slot_mulai !== slot.slot_mulai,
                                            'bg-primary-600 border-primary-600 text-white shadow-sm shadow-primary-300': selectedSlot?.slot_mulai === slot.slot_mulai,
                                            'bg-red-50 border-red-200 text-red-400 cursor-not-allowed opacity-70': slot.status === 'terisi',
                                            'bg-slate-50 border-slate-200 text-slate-300 cursor-not-allowed': slot.status === 'tidak_tersedia',
                                        }"
                                        class="px-2 py-2.5 rounded-xl border text-xs font-semibold text-center transition duration-150"
                                    >
                                        <span x-text="slot.label.replace(' WIB', '')"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Book Button --}}
                        <div x-show="selectedSlot" x-cloak class="mt-5 pt-4 border-t border-slate-100">
                            <p class="text-xs text-slate-500 mb-3">
                                Slot dipilih: <span class="font-semibold text-slate-800" x-text="selectedSlot?.label"></span>
                            </p>
                            @auth
                                @if(Auth::user()->isUser())
                                <a
                                    x-bind:href="`{{ route('user.booking.create') }}?sarana_id={{ $sarana->id }}&tanggal=${tanggal}&slot_mulai=${selectedSlot?.slot_mulai}&slot_selesai=${selectedSlot?.slot_selesai}`"
                                    class="block w-full text-center py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm transition shadow-sm shadow-primary-500/20"
                                >
                                    Booking Slot Ini →
                                </a>
                                @else
                                <p class="text-xs text-slate-500 bg-amber-50 border border-amber-100 rounded-xl p-3">
                                    Akun admin tidak dapat melakukan booking. Gunakan akun warga untuk booking.
                                </p>
                                @endif
                            @else
                            <a href="{{ route('login') }}" class="block w-full text-center py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm transition">
                                Masuk untuk Booking
                            </a>
                            @endauth
                        </div>

                        <div x-show="!selectedSlot" class="mt-5 pt-4 border-t border-slate-100">
                            <p class="text-xs text-center text-slate-400">↑ Pilih slot yang tersedia di atas untuk melanjutkan booking</p>
                        </div>
                    </div>
                </div>

                {{-- Metode Bayar Info --}}
                <div class="mt-4 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                    <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-2">Metode Pembayaran</h3>
                    <ul class="space-y-1.5 text-xs text-blue-700">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                            Transfer Bank ke rekening Dispora
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                            Bayar tunai langsung di lokasi
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x:layouts.app>
