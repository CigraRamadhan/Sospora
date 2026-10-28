<x:layouts.admin>
    <x-slot:title>Laporan Bulanan</x-slot:title>

    <div class="p-6 lg:p-8">
        <div class="mb-6">
            <h1 class="font-heading font-extrabold text-2xl text-slate-900">Laporan & Rekap Booking</h1>
            <p class="text-slate-500 text-sm mt-1">Ringkasan aktivitas dan pendapatan per bulan.</p>
        </div>

        {{-- Month/Year Filter --}}
        <form action="{{ route('superadmin.laporan.index') }}" method="GET" class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm mb-6">
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-semibold text-slate-700">Bulan:</label>
                    <select name="bulan" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                        @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm font-semibold text-slate-700">Tahun:</label>
                    <select name="tahun" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                        @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="text-sm text-slate-400">
                    Menampilkan data {{ \Carbon\Carbon::create()->month($bulan)->isoFormat('MMMM') }} {{ $tahun }}
                </span>
            </div>
        </form>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gradient-to-br from-primary-700 to-primary-600 text-white rounded-2xl p-5 shadow-md">
                <p class="text-xs text-white/60 mb-1 uppercase tracking-wider">Total Pendapatan</p>
                <p class="font-heading font-extrabold text-xl">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <p class="text-xs text-slate-400 mb-1 uppercase tracking-wider">Total Booking</p>
                <p class="font-heading font-extrabold text-2xl text-slate-900">{{ $bookings->count() }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <p class="text-xs text-slate-400 mb-1 uppercase tracking-wider">Disetujui/Selesai</p>
                <p class="font-heading font-extrabold text-2xl text-emerald-600">
                    {{ ($statsByStatus->get('disetujui', 0) + $statsByStatus->get('selesai', 0)) }}
                </p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <p class="text-xs text-slate-400 mb-1 uppercase tracking-wider">Dibatalkan/Ditolak</p>
                <p class="font-heading font-extrabold text-2xl text-red-500">
                    {{ ($statsByStatus->get('dibatalkan', 0) + $statsByStatus->get('ditolak', 0)) }}
                </p>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-6 mb-6">
            {{-- Status Breakdown --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h2 class="font-heading font-bold text-slate-800 mb-4">Distribusi Status</h2>
                @php
                $statusColors = [
                    'menunggu_pembayaran' => 'bg-amber-400',
                    'menunggu_konfirmasi' => 'bg-blue-400',
                    'disetujui' => 'bg-emerald-500',
                    'selesai' => 'bg-slate-400',
                    'ditolak' => 'bg-red-500',
                    'dibatalkan' => 'bg-slate-300',
                ];
                $statusLabels = [
                    'menunggu_pembayaran' => 'Menunggu Pembayaran',
                    'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                    'disetujui' => 'Disetujui',
                    'selesai' => 'Selesai',
                    'ditolak' => 'Ditolak',
                    'dibatalkan' => 'Dibatalkan',
                ];
                $total = $bookings->count() ?: 1;
                @endphp
                @if($bookings->isEmpty())
                <p class="text-sm text-slate-400 text-center py-6">Tidak ada data untuk periode ini.</p>
                @else
                <div class="space-y-3">
                    @foreach($statsByStatus as $status => $count)
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-slate-600">{{ $statusLabels[$status] ?? $status }}</span>
                                <span class="font-semibold text-slate-800">{{ $count }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full {{ $statusColors[$status] ?? 'bg-slate-400' }}" style="width: {{ round($count / $total * 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Per Sarana --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h2 class="font-heading font-bold text-slate-800 mb-4">Booking per Sarana</h2>
                @if($statsBySarana->isEmpty())
                <p class="text-sm text-slate-400 text-center py-6">Tidak ada data.</p>
                @else
                <div class="space-y-3">
                    @foreach($statsBySarana->sortByDesc('total')->take(8) as $item)
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-800 truncate">{{ $item->sarana?->nama_sarana ?? 'Sarana #' . $item->sarana_id }}</p>
                            <div class="h-1.5 mt-1 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-primary-500" style="width: {{ $statsBySarana->max('total') > 0 ? round($item->total / $statsBySarana->max('total') * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        <span class="font-bold text-slate-700 ml-4 flex-shrink-0">{{ $item->total }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Booking Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-heading font-bold text-slate-800">Semua Booking Bulan Ini</h2>
                <span class="text-xs text-slate-400">{{ $bookings->count() }} total</span>
            </div>
            @if($bookings->isEmpty())
            <div class="py-12">
                <x-empty-state icon="calendar" title="Tidak Ada Data" message="Belum ada booking untuk periode yang dipilih." />
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Kode</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider hidden md:table-cell">Warga</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider hidden lg:table-cell">Sarana</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider hidden md:table-cell">Harga</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($bookings as $booking)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.booking.show', $booking) }}" class="font-mono text-xs font-semibold text-primary-600 hover:text-primary-800">{{ $booking->kode_booking }}</a>
                            </td>
                            <td class="px-5 py-3 hidden md:table-cell text-xs text-slate-600">{{ $booking->user->nama }}</td>
                            <td class="px-5 py-3 hidden lg:table-cell text-xs text-slate-600">{{ $booking->sarana->nama_sarana }}</td>
                            <td class="px-5 py-3 text-xs text-slate-600">{{ \Carbon\Carbon::parse($booking->tanggal)->isoFormat('D MMM') }}</td>
                            <td class="px-5 py-3 hidden md:table-cell text-xs font-semibold text-slate-700">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-3"><x-status-badge :status="$booking->status" /></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</x:layouts.admin>
