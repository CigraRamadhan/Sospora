<x:layouts.app>
    <x-slot:title>Form Booking</x-slot:title>

    <div
        class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
        x-data="{
            metodeBayar: '{{ old('metode_bayar', 'transfer') }}',
            buktiFile: null,
            jumlahPeserta: {{ old('jumlah_peserta', 1) }},
            hargaPerSlot: {{ (float) $sarana->harga_per_slot }},
            get totalHarga() {
                const count = Math.max(1, parseInt(this.jumlahPeserta) || 1);
                return count * this.hargaPerSlot;
            },
            formatRupiah(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }
        }"
    >

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
            <a href="{{ route('sarana.show', $sarana->slug) }}" class="hover:text-primary-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                Kembali ke {{ $sarana->nama_sarana }}
            </a>
        </nav>

        <h1 class="font-heading font-extrabold text-2xl md:text-3xl text-slate-900 mb-6">Konfirmasi Booking</h1>

        {{-- Booking Summary Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
            <div class="flex items-center gap-4 pb-4 border-b border-slate-100 mb-4">
                @if($sarana->foto_utama)
                <img src="{{ $sarana->foto_utama }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                @else
                <div class="w-16 h-16 rounded-xl bg-primary-50 flex items-center justify-center text-3xl flex-shrink-0">{{ $sarana->kategori->icon ?? '🏟️' }}</div>
                @endif
                <div>
                    <p class="font-semibold text-slate-800">{{ $sarana->nama_sarana }}</p>
                    <p class="text-sm text-slate-500">{{ $sarana->lokasi }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-500 mb-1">Tanggal</p>
                    <p class="font-semibold text-sm text-slate-800">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMM Y') }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-500 mb-1">Waktu</p>
                    <p class="font-semibold text-sm text-slate-800">{{ $selectedSlot['label'] }}</p>
                </div>
                <div class="bg-primary-50 rounded-xl p-3">
                    <p class="text-xs text-slate-500 mb-1">Total Biaya (<span x-text="Math.max(1, parseInt(jumlahPeserta) || 1)"></span> jam)</p>
                    <p class="font-extrabold text-primary-700 text-sm">Rp <span x-text="formatRupiah(totalHarga)">{{ number_format($sarana->harga_per_slot, 0, ',', '.') }}</span></p>
                </div>
            </div>
        </div>

        {{-- Booking Form --}}
        <form
            action="{{ route('user.booking.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            <input type="hidden" name="sarana_id" value="{{ $sarana->id }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            <input type="hidden" name="slot_mulai" value="{{ $slotMulai }}">
            <input type="hidden" name="slot_selesai" value="{{ $selectedSlot['slot_selesai'] }}">

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm mb-5">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">

                {{-- Jumlah Peserta --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="jumlah_peserta" class="block text-sm font-semibold text-slate-700">
                            Jumlah Jam <span class="text-red-500">*</span>
                        </label>
                        <span class="text-xs text-slate-500">
                            Tarif: Rp {{ number_format($sarana->harga_per_slot, 0, ',', '.') }} / jam
                        </span>
                    </div>
                    <input
                        type="number"
                        id="jumlah_peserta"
                        name="jumlah_peserta"
                        x-model.number="jumlahPeserta"
                        value="{{ old('jumlah_peserta', 1) }}"
                        min="1"
                        max="100"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 @error('jumlah_peserta') border-red-400 @enderror"
                        required
                    >
                    <div class="mt-2 p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs text-slate-600">
                        <span>Perhitungan: <strong x-text="Math.max(1, parseInt(jumlahPeserta) || 1)">1</strong> jam × Rp {{ number_format($sarana->harga_per_slot, 0, ',', '.') }}</span>
                        <span class="font-bold text-primary-700 text-sm">Total: Rp <span x-text="formatRupiah(totalHarga)">{{ number_format($sarana->harga_per_slot, 0, ',', '.') }}</span></span>
                    </div>
                    @error('jumlah_peserta') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Catatan --}}
                <div>
                    <label for="catatan_user" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Catatan (opsional)
                    </label>
                    <textarea
                        id="catatan_user"
                        name="catatan_user"
                        rows="3"
                        placeholder="Informasi tambahan untuk pengelola..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none @error('catatan_user') border-red-400 @enderror"
                    >{{ old('catatan_user') }}</textarea>
                </div>

                {{-- Metode Bayar --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Metode Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label
                            x-bind:class="metodeBayar === 'transfer' ? 'border-primary-500 bg-primary-50 ring-2 ring-primary-400' : 'border-slate-200 hover:border-slate-300'"
                            class="flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition"
                        >
                            <input type="radio" name="metode_bayar" value="transfer" x-model="metodeBayar" class="text-primary-600 sr-only">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-sm text-slate-800">Transfer Bank</p>
                                <p class="text-xs text-slate-500">Upload bukti transfer</p>
                            </div>
                        </label>

                        <label
                            x-bind:class="metodeBayar === 'bayar_di_tempat' ? 'border-primary-500 bg-primary-50 ring-2 ring-primary-400' : 'border-slate-200 hover:border-slate-300'"
                            class="flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition"
                        >
                            <input type="radio" name="metode_bayar" value="bayar_di_tempat" x-model="metodeBayar" class="text-primary-600 sr-only">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-sm text-slate-800">Bayar di Tempat</p>
                                <p class="text-xs text-slate-500">Bayar tunai saat tiba</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Rekening Info (for transfer) --}}
                @if($pengaturan)
                <div x-show="metodeBayar === 'transfer'" class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <p class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-2">Informasi Rekening Transfer</p>
                    <div class="space-y-1 text-sm text-blue-700">
                        <p><span class="font-medium">Bank:</span> {{ $pengaturan->nama_bank }}</p>
                        <p><span class="font-medium">No. Rekening:</span> <strong class="text-blue-900">{{ $pengaturan->nomor_rekening }}</strong></p>
                        <p><span class="font-medium">Atas Nama:</span> {{ $pengaturan->atas_nama }}</p>
                        <p><span class="font-medium">Jumlah Transfer:</span> <strong class="text-blue-900">Rp <span x-text="formatRupiah(totalHarga)">{{ number_format($sarana->harga_per_slot, 0, ',', '.') }}</span></strong></p>
                    </div>
                </div>
                @endif

                {{-- Bukti Transfer Upload --}}
                <div x-show="metodeBayar === 'transfer'">
                    <label for="bukti_transfer" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Upload Bukti Transfer
                        <span class="text-slate-400 font-normal text-xs">(JPG/PNG, max 2MB)</span>
                    </label>
                    <div
                        x-on:dragover.prevent
                        x-on:drop.prevent="buktiFile = $event.dataTransfer.files[0]; $refs.buktiInput.files = $event.dataTransfer.files"
                        class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-primary-400 transition cursor-pointer"
                        x-on:click="$refs.buktiInput.click()"
                    >
                        <div x-show="!buktiFile">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                            <p class="text-sm text-slate-500">Klik atau drag & drop foto bukti transfer</p>
                        </div>
                        <div x-show="buktiFile" class="text-sm text-emerald-600 font-medium">
                            ✓ <span x-text="buktiFile?.name"></span>
                        </div>
                    </div>
                    <input
                        type="file"
                        id="bukti_transfer"
                        name="bukti_transfer"
                        accept="image/*"
                        x-ref="buktiInput"
                        x-on:change="buktiFile = $event.target.files[0]"
                        class="hidden"
                    >
                    @error('bukti_transfer') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div x-show="metodeBayar === 'bayar_di_tempat'" class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-sm text-amber-800">
                    ⚠️ Dengan memilih bayar di tempat, Anda berkomitmen untuk hadir dan membayar tunai kepada petugas Dispora. Admin akan mengonfirmasi kedatangan Anda.
                </div>
            </div>

            {{-- Submit --}}
            <div class="mt-6 flex items-center gap-4">
                <a href="{{ route('sarana.show', $sarana->slug) }}" class="flex-1 text-center py-3.5 rounded-2xl border border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="flex-1 py-3.5 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm transition shadow-md shadow-primary-500/20 hover:scale-[1.02] active:scale-[0.98]">
                    Konfirmasi Booking (Rp <span x-text="formatRupiah(totalHarga)">{{ number_format($sarana->harga_per_slot, 0, ',', '.') }}</span>) →
                </button>
            </div>
        </form>
    </div>
</x:layouts.app>
