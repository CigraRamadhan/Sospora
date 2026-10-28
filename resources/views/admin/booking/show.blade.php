<x:layouts.admin>
    <x-slot:title>Detail Booking {{ $booking->kode_booking }}</x-slot:title>

    <div class="p-6 lg:p-8 max-w-4xl">
        <a href="{{ route('admin.booking.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-primary-600 mb-6 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            Kembali ke Daftar Booking
        </a>

        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <div>
                <h1 class="font-heading font-extrabold text-xl text-slate-900">Detail Booking</h1>
                <p class="text-sm font-mono text-slate-500">{{ $booking->kode_booking }}</p>
            </div>
            <x-status-badge :status="$booking->status" />
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            {{-- Left: Info --}}
            <div class="space-y-5">
                {{-- Sarana & Schedule --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h2 class="font-heading font-bold text-slate-800 mb-4 text-sm uppercase tracking-wider text-slate-500">Informasi Pemesanan</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between"><span class="text-slate-500">Warga</span><span class="font-semibold">{{ $booking->user->nama }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Email</span><span class="text-xs">{{ $booking->user->email }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">No. HP</span><span>{{ $booking->user->no_hp }}</span></div>
                        <div class="border-t border-slate-100 pt-3"></div>
                        <div class="flex justify-between"><span class="text-slate-500">Sarana</span><span class="font-semibold text-right max-w-[180px]">{{ $booking->sarana->nama_sarana }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Tanggal</span><span class="font-semibold">{{ \Carbon\Carbon::parse($booking->tanggal)->isoFormat('dddd, D MMMM Y') }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Waktu</span><span class="font-semibold">{{ substr($booking->slot_mulai, 0, 5) }} – {{ substr($booking->slot_selesai, 0, 5) }} WIB</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Jumlah Jam</span><span>{{ $booking->jumlah_peserta }} jam</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Tarif per Jam</span><span class="text-slate-700">Rp {{ number_format($booking->sarana->harga_per_slot, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Total Biaya ({{ $booking->jumlah_peserta }} jam)</span><span class="font-extrabold text-primary-700">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Metode Bayar</span>
                            <span class="px-2 py-0.5 rounded-lg text-xs font-semibold {{ $booking->metode_bayar === 'transfer' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                                {{ $booking->metode_bayar === 'transfer' ? 'Transfer Bank' : 'Bayar di Tempat' }}
                            </span>
                        </div>
                    </div>
                    @if($booking->catatan_user)
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <p class="text-xs text-slate-500 mb-1">Catatan Warga:</p>
                        <p class="text-sm text-slate-700">{{ $booking->catatan_user }}</p>
                    </div>
                    @endif
                </div>

                {{-- Admin Action --}}
                @if(in_array($booking->status, ['menunggu_konfirmasi', 'menunggu_pembayaran']))
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
                    <h2 class="font-heading font-bold text-sm text-slate-500 uppercase tracking-wider">Tindakan Admin</h2>

                    {{-- Konfirmasi cash --}}
                    @if($booking->metode_bayar === 'bayar_di_tempat')
                    <form action="{{ route('admin.booking.konfirmasi', $booking) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition shadow-sm">
                            ✓ Konfirmasi Pembayaran di Tempat
                        </button>
                    </form>
                    @endif

                    {{-- Setujui --}}
                    <form action="{{ route('admin.booking.setujui', $booking) }}" method="POST" class="space-y-2">
                        @csrf
                        <textarea name="catatan_admin" rows="2" placeholder="Catatan untuk warga (opsional)..." class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none"></textarea>
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm transition">
                            ✓ Setujui Booking
                        </button>
                    </form>

                    {{-- Tolak --}}
                    <form action="{{ route('admin.booking.tolak', $booking) }}" method="POST" class="space-y-2" x-data="{ showTolak: false }">
                        @csrf
                        <button type="button" x-on:click="showTolak = !showTolak" class="w-full py-2.5 rounded-xl border border-red-200 text-red-600 font-semibold text-sm hover:bg-red-50 transition">
                            ✕ Tolak Booking
                        </button>
                        <div x-show="showTolak" x-cloak class="space-y-2">
                            <textarea name="catatan_admin" rows="2" placeholder="Alasan penolakan (wajib)..." required class="w-full px-3 py-2 text-sm rounded-xl border border-red-200 focus:outline-none focus:ring-2 focus:ring-red-400 resize-none"></textarea>
                            <button type="submit" class="w-full py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition">Konfirmasi Tolak</button>
                        </div>
                    </form>
                </div>
                @endif

                @if($booking->status === 'disetujui')
                <form action="{{ route('admin.booking.selesai', $booking) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 rounded-2xl bg-slate-700 hover:bg-slate-800 text-white font-bold text-sm transition shadow-sm">
                        Tandai Selesai
                    </button>
                </form>
                @endif

                @if($booking->catatan_admin)
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                    <p class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-1">Catatan Admin</p>
                    <p class="text-sm text-blue-800">{{ $booking->catatan_admin }}</p>
                    @if($booking->verifikator) <p class="text-xs text-blue-500 mt-2">— {{ $booking->verifikator->nama }}, {{ $booking->diverifikasi_pada?->isoFormat('D MMM Y HH:mm') }}</p> @endif
                </div>
                @endif
            </div>

            {{-- Right: Payment Proof --}}
            <div>
                @if($booking->pembayaran)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h2 class="font-heading font-bold text-sm text-slate-500 uppercase tracking-wider mb-4">Bukti Pembayaran</h2>

                    @if($booking->pembayaran->bukti_transfer)
                    <a href="{{ route('admin.booking.bukti', $booking) }}" target="_blank">
                        <img src="{{ route('admin.booking.bukti', $booking) }}" class="w-full rounded-xl border border-slate-200 hover:opacity-90 transition mb-3">
                    </a>
                    <a href="{{ route('admin.booking.bukti', $booking) }}" target="_blank" class="text-xs text-center text-primary-600 hover:underline block font-medium">Buka bukti transfer ukuran penuh ↗</a>
                    @else
                    <div class="h-40 bg-slate-50 rounded-xl flex items-center justify-center border border-dashed border-slate-200">
                        <p class="text-sm text-slate-400">
                            {{ $booking->metode_bayar === 'transfer' ? 'Bukti transfer belum diupload' : 'Bayar di tempat (tanpa bukti transfer)' }}
                        </p>
                    </div>
                    @endif

                    <div class="mt-4 pt-4 border-t border-slate-100 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Jumlah</span>
                            <span class="font-bold">Rp {{ number_format($booking->pembayaran->jumlah, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Metode</span>
                            <span class="capitalize">{{ $booking->pembayaran->metode }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Status Verifikasi</span>
                            @php
                            $cls = match($booking->pembayaran->status_verifikasi) {
                                'terverifikasi' => 'bg-emerald-100 text-emerald-700',
                                'ditolak' => 'bg-red-100 text-red-700',
                                default => 'bg-amber-100 text-amber-700',
                            };
                            @endphp
                            <span class="px-2 py-0.5 rounded-lg text-xs font-semibold {{ $cls }} capitalize">{{ str_replace('_', ' ', $booking->pembayaran->status_verifikasi) }}</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-8 text-center text-slate-400">
                    <p class="text-sm">Belum ada data pembayaran</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x:layouts.admin>
