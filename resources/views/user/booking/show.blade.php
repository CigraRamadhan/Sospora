<x:layouts.app>
    <x-slot:title>Detail Booking {{ $booking->kode_booking }}</x-slot:title>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('user.booking.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-primary-600 mb-6 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            Kembali ke Booking Saya
        </a>

        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-extrabold text-xl md:text-2xl text-slate-900">Detail Booking</h1>
            <x-status-badge :status="$booking->status" />
        </div>

        {{-- Booking Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-5">
            {{-- Header --}}
            <div class="bg-gradient-to-br from-primary-800 to-primary-700 px-5 py-4">
                <p class="text-xs text-white/60 font-medium uppercase tracking-wider">Kode Booking</p>
                <p class="font-mono font-bold text-lg text-white">{{ $booking->kode_booking }}</p>
            </div>

            {{-- Info Grid --}}
            <div class="p-5 grid grid-cols-2 gap-4 border-b border-slate-100">
                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Sarana</p>
                    <p class="font-semibold text-sm text-slate-800">{{ $booking->sarana->nama_sarana }}</p>
                    <p class="text-xs text-slate-500">{{ $booking->sarana->lokasi }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Tanggal</p>
                    <p class="font-semibold text-sm text-slate-800">{{ \Carbon\Carbon::parse($booking->tanggal)->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Waktu</p>
                    <p class="font-semibold text-sm text-slate-800">{{ substr($booking->slot_mulai, 0, 5) }} – {{ substr($booking->slot_selesai, 0, 5) }} WIB</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Jumlah Jam</p>
                    <p class="font-semibold text-sm text-slate-800">{{ $booking->jumlah_peserta }} jam</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Metode Bayar</p>
                    <p class="font-semibold text-sm text-slate-800 capitalize">{{ $booking->metode_bayar === 'transfer' ? 'Transfer Bank' : 'Bayar di Tempat' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Total</p>
                    <p class="font-extrabold text-primary-700">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>

            @if($booking->catatan_user)
            <div class="px-5 py-4 border-b border-slate-100">
                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Catatan Anda</p>
                <p class="text-sm text-slate-700">{{ $booking->catatan_user }}</p>
            </div>
            @endif

            @if($booking->catatan_admin)
            <div class="px-5 py-4 border-b border-slate-100 bg-blue-50">
                <p class="text-xs text-blue-500 font-medium uppercase tracking-wider mb-1">Catatan Admin</p>
                <p class="text-sm text-blue-800">{{ $booking->catatan_admin }}</p>
            </div>
            @endif

            {{-- Payment Info --}}
            @if($booking->pembayaran)
            <div class="px-5 py-4 border-b border-slate-100">
                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-3">Pembayaran</p>
                @if($booking->pembayaran->bukti_transfer)
                <div class="mb-3">
                    <p class="text-xs text-slate-500 mb-2">Bukti Transfer:</p>
                    <a href="{{ route('user.booking.bukti', $booking) }}" target="_blank">
                        <img src="{{ route('user.booking.bukti', $booking) }}" class="w-full max-h-48 object-cover rounded-xl border border-slate-200 hover:opacity-90 transition">
                    </a>
                </div>
                @endif
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-600">Status Verifikasi:</p>
                    @php
                    $verifikasiBadge = match($booking->pembayaran->status_verifikasi) {
                        'menunggu' => 'bg-amber-100 text-amber-700',
                        'terverifikasi' => 'bg-emerald-100 text-emerald-700',
                        'ditolak' => 'bg-red-100 text-red-700',
                        default => 'bg-slate-100 text-slate-600',
                    };
                    $verifikasiLabel = match($booking->pembayaran->status_verifikasi) {
                        'menunggu' => 'Menunggu Verifikasi',
                        'terverifikasi' => 'Terverifikasi',
                        'ditolak' => 'Ditolak',
                        default => ucfirst($booking->pembayaran->status_verifikasi),
                    };
                    @endphp
                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $verifikasiBadge }}">{{ $verifikasiLabel }}</span>
                </div>
            </div>
            @endif

            {{-- Transfer info (if transfer but no proof yet) --}}
            @if($booking->status === 'menunggu_pembayaran' && $booking->metode_bayar === 'transfer' && $pengaturan)
            <div class="px-5 py-4 bg-amber-50">
                <p class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-2">Instruksi Transfer</p>
                <div class="text-sm text-amber-700 space-y-1">
                    <p><span class="font-medium">Bank:</span> {{ $pengaturan->nama_bank }}</p>
                    <p><span class="font-medium">No. Rekening:</span> <strong>{{ $pengaturan->nomor_rekening }}</strong></p>
                    <p><span class="font-medium">Atas Nama:</span> {{ $pengaturan->atas_nama }}</p>
                    <p><span class="font-medium">Jumlah:</span> <strong>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</strong></p>
                </div>
                <form action="{{ route('user.booking.upload-bukti', $booking) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <label class="block text-xs font-semibold text-amber-800 mb-1.5">Upload Bukti Transfer</label>
                    <div class="flex items-center gap-2">
                        <input type="file" name="bukti_transfer" accept="image/*" class="flex-1 text-xs file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition">Upload</button>
                    </div>
                </form>
            </div>
            @endif

            {{-- Verification Info --}}
            @if($booking->verifikator)
            <div class="px-5 py-4 bg-slate-50">
                <p class="text-xs text-slate-400">Diverifikasi oleh <span class="font-medium text-slate-700">{{ $booking->verifikator->nama }}</span> pada {{ $booking->diverifikasi_pada->isoFormat('D MMM Y, HH:mm') }} WIB</p>
            </div>
            @endif
        </div>

        {{-- Cancel Button --}}
        @if($booking->canBeCancelledByUser())
        <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-5">
            <p class="text-sm text-slate-600 mb-3">Tidak jadi? Anda masih bisa membatalkan booking ini.</p>
            <x-confirm-modal
                title="Batalkan Booking?"
                message="Booking {{ $booking->kode_booking }} untuk {{ $booking->sarana->nama_sarana }} akan dibatalkan permanen."
                action-label="Ya, Batalkan Booking Ini"
                :action-url="route('user.booking.cancel', $booking)"
                trigger-class="w-full py-3 rounded-xl bg-red-50 border border-red-200 text-sm font-semibold text-red-600 hover:bg-red-100 transition"
                trigger-label="Batalkan Booking Ini"
            />
        </div>
        @endif
    </div>
</x:layouts.app>
