<x:layouts.admin>
    <x-slot:title>Dashboard Super Admin</x-slot:title>

    <div class="p-6 lg:p-8">
        <div class="mb-8">
            <h1 class="font-heading font-extrabold text-2xl text-slate-900">Dashboard Super Admin</h1>
            <p class="text-slate-500 text-sm mt-1">Ringkasan statistik sistem SOSPORA Kota Bandung.</p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @php
            $cards = [
                ['label' => 'Total Sarana', 'value' => $stats['total_sarana'], 'sub' => $stats['sarana_aktif'] . ' aktif', 'color' => 'primary', 'icon' => 'M2.25 21h19.5m-18-18v18m10.5-18v18'],
                ['label' => 'Admin Aktif', 'value' => $stats['total_admin'], 'sub' => 'Operator sistem', 'color' => 'blue', 'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
                ['label' => 'Total Warga', 'value' => $stats['total_user'], 'sub' => 'Akun terdaftar', 'color' => 'emerald', 'icon' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z'],
                ['label' => 'Booking Bulan Ini', 'value' => $stats['booking_bulan_ini'], 'sub' => 'Total: ' . $stats['total_booking'], 'color' => 'amber', 'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25'],
            ];
            @endphp
            @foreach($cards as $card)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-{{ $card['color'] }}-50 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-{{ $card['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                    </svg>
                </div>
                <p class="font-heading font-extrabold text-2xl text-slate-900">{{ $card['value'] }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $card['label'] }}</p>
                <p class="text-[11px] text-slate-400 mt-1">{{ $card['sub'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Revenue this month --}}
        <div class="grid md:grid-cols-3 gap-4 mb-8">
            <div class="md:col-span-1 bg-gradient-to-br from-primary-800 to-primary-700 text-white rounded-2xl p-5 shadow-md">
                <p class="text-xs text-white/60 font-medium uppercase tracking-wider mb-1">Pendapatan Bulan Ini</p>
                <p class="font-heading font-extrabold text-2xl">Rp {{ number_format($stats['pendapatan_bulan_ini'], 0, ',', '.') }}</p>
                <p class="text-xs text-white/60 mt-1">dari pembayaran terverifikasi</p>
            </div>
            <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex items-center justify-center">
                <div class="text-center">
                    <p class="text-4xl font-extrabold text-amber-600">{{ $stats['booking_menunggu'] }}</p>
                    <p class="text-sm text-slate-500 mt-1">Booking Menunggu Konfirmasi</p>
                    @if($stats['booking_menunggu'] > 0)
                    <a href="{{ route('admin.booking.index', ['status' => 'menunggu_konfirmasi']) }}" class="inline-block mt-3 px-4 py-2 rounded-xl bg-amber-100 text-amber-700 text-xs font-semibold hover:bg-amber-200 transition">
                        Tinjau Sekarang →
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Bookings --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h2 class="font-heading font-bold text-slate-900">Booking Terbaru</h2>
                <a href="{{ route('admin.booking.index') }}" class="text-xs text-primary-600 font-semibold hover:text-primary-800">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-slate-50">
                        @foreach($bookingTerbaru as $booking)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-5 py-3">
                                <p class="font-mono text-xs font-semibold">{{ $booking->kode_booking }}</p>
                                <p class="text-xs text-slate-500">{{ $booking->user->nama }}</p>
                            </td>
                            <td class="px-5 py-3 hidden sm:table-cell">
                                <p class="text-xs text-slate-700">{{ $booking->sarana->nama_sarana }}</p>
                                <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($booking->tanggal)->isoFormat('D MMM') }}</p>
                            </td>
                            <td class="px-5 py-3"><x-status-badge :status="$booking->status" /></td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.booking.show', $booking) }}" class="text-xs text-primary-600 font-semibold hover:text-primary-800">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x:layouts.admin>
