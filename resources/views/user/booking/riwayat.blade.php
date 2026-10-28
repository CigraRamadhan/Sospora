<x:layouts.app>
    <x-slot:title>Riwayat Booking</x-slot:title>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h1 class="font-heading font-extrabold text-2xl md:text-3xl text-slate-900">Riwayat Booking</h1>
            <p class="text-slate-500 text-sm mt-1">Booking yang telah selesai, dibatalkan, atau ditolak</p>
        </div>

        @if($bookings->isEmpty())
            <x-empty-state icon="archive-box" title="Belum Ada Riwayat" message="Belum ada riwayat booking yang selesai atau dibatalkan." />
        @else
        <div class="space-y-3">
            @foreach($bookings as $booking)
            <div class="bg-white rounded-2xl border border-slate-200 p-4 flex items-center gap-4 hover:shadow-sm transition">
                <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0">
                    @if($booking->sarana->foto_utama)
                    <img src="{{ $booking->sarana->foto_utama }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-xl">{{ $booking->sarana->kategori->icon ?? '🏟️' }}</div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm text-slate-800 truncate">{{ $booking->sarana->nama_sarana }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ \Carbon\Carbon::parse($booking->tanggal)->isoFormat('D MMM Y') }} • {{ substr($booking->slot_mulai, 0, 5) }}–{{ substr($booking->slot_selesai, 0, 5) }}
                    </p>
                    <p class="text-[11px] font-mono text-slate-400 mt-0.5">{{ $booking->kode_booking }}</p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <p class="text-sm font-bold text-slate-700 hidden sm:block">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                    <x-status-badge :status="$booking->status" />
                    <a href="{{ route('user.booking.show', $booking->kode_booking) }}" class="p-2 rounded-xl text-slate-400 hover:text-primary-600 hover:bg-slate-100 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        @if($bookings->hasPages())
        <div class="mt-6">{{ $bookings->links() }}</div>
        @endif
        @endif
    </div>
</x:layouts.app>
