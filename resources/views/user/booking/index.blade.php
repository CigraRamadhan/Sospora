<x:layouts.app>
    <x-slot:title>Booking Saya</x-slot:title>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="font-heading font-extrabold text-2xl md:text-3xl text-slate-900">Booking Saya</h1>
                <p class="text-slate-500 text-sm mt-1">Booking aktif yang sedang berjalan</p>
            </div>
            <a href="{{ route('sarana.index') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Booking Baru
            </a>
        </div>

        @if($bookings->isEmpty())
            <x-empty-state icon="calendar" title="Belum Ada Booking Aktif" message="Anda belum memiliki booking yang sedang aktif." :action-href="route('sarana.index')" action-label="Cari Sarana & Booking Sekarang" />
        @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex flex-col sm:flex-row">
                    {{-- Image --}}
                    <div class="sm:w-32 sm:h-auto h-32 flex-shrink-0 bg-slate-100 overflow-hidden">
                        @if($booking->sarana->foto_utama)
                        <img src="{{ $booking->sarana->foto_utama }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-3xl">{{ $booking->sarana->kategori->icon ?? '🏟️' }}</div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 p-5">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $booking->sarana->nama_sarana }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $booking->sarana->lokasi }}</p>
                                <div class="flex items-center gap-3 mt-2 text-xs text-slate-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" /></svg>
                                        {{ \Carbon\Carbon::parse($booking->tanggal)->isoFormat('D MMM Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        {{ substr($booking->slot_mulai, 0, 5) }} – {{ substr($booking->slot_selesai, 0, 5) }} WIB
                                    </span>
                                </div>
                                <p class="text-[11px] font-mono text-slate-400 mt-1">{{ $booking->kode_booking }}</p>
                            </div>
                            <x-status-badge :status="$booking->status" />
                        </div>

                        {{-- Upload bukti (for menunggu_pembayaran + transfer) --}}
                        @if($booking->status === 'menunggu_pembayaran' && $booking->metode_bayar === 'transfer')
                        <div x-data="{ openUpload: false, previewUrl: null }" class="mt-4">
                            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-center justify-between gap-3">
                                <p class="text-xs text-amber-800"><span class="font-semibold">⏳ Menunggu bukti transfer.</span> Segera upload bukti transfer untuk diproses admin.</p>
                                <button
                                    type="button"
                                    x-on:click="openUpload = true"
                                    class="shrink-0 px-3 py-1.5 rounded-lg bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition cursor-pointer"
                                >
                                    Upload Bukti
                                </button>
                            </div>

                            <!-- Dedicated Upload Modal -->
                            <div
                                x-show="openUpload"
                                x-cloak
                                class="fixed inset-0 z-50 overflow-y-auto"
                                style="display: none;"
                            >
                                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" x-on:click="openUpload = false"></div>

                                <div class="flex min-h-full items-center justify-center p-4">
                                    <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left shadow-2xl transition-all border border-slate-100" x-on:click.stop>
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="font-heading font-bold text-lg text-slate-900">Upload Bukti Transfer</h3>
                                            <button type="button" x-on:click="openUpload = false" class="text-slate-400 hover:text-slate-600">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>

                                        <div class="mb-4 p-3 bg-slate-50 rounded-xl text-xs space-y-1 text-slate-600">
                                            <p><span class="text-slate-400">Kode Booking:</span> <strong class="text-slate-800 font-mono">{{ $booking->kode_booking }}</strong></p>
                                            <p><span class="text-slate-400">Sarana:</span> {{ $booking->sarana->nama_sarana }} ({{ $booking->jumlah_peserta }} jam)</p>
                                            <p><span class="text-slate-400">Total Biaya:</span> <strong class="text-primary-700 text-sm">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</strong></p>
                                        </div>

                                        <form action="{{ route('user.booking.upload-bukti', $booking) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                            @csrf
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Foto Bukti Transfer</label>
                                                <input
                                                    type="file"
                                                    name="bukti_transfer"
                                                    accept="image/*"
                                                    required
                                                    x-on:change="previewUrl = URL.createObjectURL($event.target.files[0])"
                                                    class="w-full text-xs file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer border border-slate-200 rounded-xl p-2"
                                                >
                                                <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 2MB)</p>
                                            </div>

                                            <template x-if="previewUrl">
                                                <div class="mt-2">
                                                    <p class="text-xs text-slate-500 mb-1">Preview:</p>
                                                    <img :src="previewUrl" class="w-full max-h-48 object-contain rounded-xl border border-slate-200">
                                                </div>
                                            </template>

                                            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                                                <button
                                                    type="button"
                                                    x-on:click="openUpload = false"
                                                    class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition"
                                                >
                                                    Batal
                                                </button>
                                                <button
                                                    type="submit"
                                                    class="px-4 py-2 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition shadow-sm"
                                                >
                                                    Unggah Bukti Transfer
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($booking->pembayaran?->bukti_transfer)
                        <div class="mt-3">
                            <a href="{{ route('user.booking.bukti', $booking) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-primary-600 hover:text-primary-800 hover:underline font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                Lihat Bukti Transfer Terunggah
                            </a>
                        </div>
                        @endif

                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100">
                            <p class="text-sm font-bold text-slate-700">Rp {{ number_format($booking->total_harga, 0, ',', '.') }} <span class="text-xs font-normal text-slate-400">({{ $booking->jumlah_peserta }} jam)</span></p>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('user.booking.show', $booking->kode_booking) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                    Detail
                                </a>
                                @if($booking->canBeCancelledByUser())
                                <x-confirm-modal
                                    title="Batalkan Booking?"
                                    message="Booking {{ $booking->kode_booking }} untuk {{ $booking->sarana->nama_sarana }} akan dibatalkan. Aksi ini tidak dapat diurungkan."
                                    action-label="Ya, Batalkan"
                                    :action-url="route('user.booking.cancel', $booking)"
                                    trigger-class="px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 text-xs font-semibold text-red-600 hover:bg-red-100 transition"
                                    trigger-label="Batalkan"
                                />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($bookings->hasPages())
        <div class="mt-6">{{ $bookings->links() }}</div>
        @endif
        @endif
    </div>
</x:layouts.app>
