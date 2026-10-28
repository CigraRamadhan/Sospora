<x:layouts.admin>
    <x-slot:title>Dashboard Admin</x-slot:title>

    <div class="p-6 lg:p-8">
        <div class="mb-8">
            <h1 class="font-heading font-extrabold text-2xl text-slate-900">Dashboard Operasional</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola dan verifikasi booking sarana olahraga Dispora.</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @php
            $cards = [
                ['label' => 'Menunggu Verifikasi', 'value' => $stats['menunggu_verifikasi'], 'color' => 'amber', 'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Disetujui Hari Ini', 'value' => $stats['disetujui_hari_ini'], 'color' => 'emerald', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Sarana Aktif', 'value' => $stats['total_sarana_aktif'], 'color' => 'primary', 'icon' => 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21'],
                ['label' => 'Booking Hari Ini', 'value' => $stats['booking_hari_ini'], 'color' => 'slate', 'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25'],
            ];
            @endphp
            @foreach($cards as $card)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-{{ $card['color'] }}-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-{{ $card['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                        </svg>
                    </div>
                    @if($card['label'] === 'Menunggu Verifikasi' && $card['value'] > 0)
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Perlu Aksi</span>
                    @endif
                </div>
                <p class="font-heading font-extrabold text-2xl text-slate-900">{{ $card['value'] }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $card['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Pending Bookings --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h2 class="font-heading font-bold text-slate-900">Booking Menunggu Konfirmasi</h2>
                <a href="{{ route('admin.booking.index', ['status' => 'menunggu_konfirmasi']) }}" class="text-xs text-primary-600 font-semibold hover:text-primary-800">Lihat Semua →</a>
            </div>

            @if($bookingMenunggu->isEmpty())
            <div class="py-12">
                <x-empty-state icon="check-circle" title="Tidak Ada Booking Menunggu" message="Semua booking sudah ditangani. 👍" />
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Kode & User</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Sarana & Jadwal</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Metode Bayar</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-right px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($bookingMenunggu as $booking)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-5 py-4">
                                <p class="font-mono text-xs text-slate-800 font-semibold">{{ $booking->kode_booking }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $booking->user->nama }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-medium text-slate-800 text-xs">{{ $booking->sarana->nama_sarana }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($booking->tanggal)->isoFormat('D MMM Y') }} • {{ substr($booking->slot_mulai, 0, 5) }}–{{ substr($booking->slot_selesai, 0, 5) }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium {{ $booking->metode_bayar === 'transfer' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $booking->metode_bayar === 'transfer' ? '🏦 Transfer' : '💵 Di Tempat' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <x-status-badge :status="$booking->status" />
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.booking.show', $booking) }}" class="px-3 py-1.5 rounded-lg bg-primary-600 text-white text-xs font-semibold hover:bg-primary-700 transition">
                                    Tinjau
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</x:layouts.admin>
