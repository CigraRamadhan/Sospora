<x:layouts.admin>
    <x-slot:title>Kelola Booking</x-slot:title>

    <div class="p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-extrabold text-2xl text-slate-900">Kelola Booking</h1>
        </div>

        {{-- Filters --}}
        <form action="{{ route('admin.booking.index') }}" method="GET" class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode / nama warga..." class="col-span-2 md:col-span-1 px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">

                <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    <option value="">Semua Status</option>
                    @foreach(['menunggu_pembayaran' => 'Menunggu Pembayaran', 'menunggu_konfirmasi' => 'Menunggu Konfirmasi', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <select name="sarana_id" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    <option value="">Semua Sarana</option>
                    @foreach($saranas as $s)
                    <option value="{{ $s->id }}" {{ request('sarana_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_sarana }}</option>
                    @endforeach
                </select>

                <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition">Filter</button>
                    @if(request()->hasAny(['search', 'status', 'sarana_id', 'tanggal']))
                    <a href="{{ route('admin.booking.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            @if($bookings->isEmpty())
            <div class="py-12">
                <x-empty-state icon="calendar" title="Tidak Ada Booking" message="Tidak ada booking yang sesuai filter." />
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Booking</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider hidden md:table-cell">Sarana & Jadwal</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider hidden lg:table-cell">Bayar</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-right px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($bookings as $booking)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-5 py-4">
                                <p class="font-mono text-xs font-semibold text-slate-800">{{ $booking->kode_booking }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $booking->user->nama }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $booking->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-5 py-4 hidden md:table-cell">
                                <p class="text-xs font-medium text-slate-800">{{ $booking->sarana->nama_sarana }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($booking->tanggal)->isoFormat('D MMM Y') }}</p>
                                <p class="text-xs text-slate-400">{{ substr($booking->slot_mulai, 0, 5) }}–{{ substr($booking->slot_selesai, 0, 5) }}</p>
                            </td>
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <span class="text-xs px-2 py-1 rounded-lg {{ $booking->metode_bayar === 'transfer' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $booking->metode_bayar === 'transfer' ? 'Transfer' : 'Di Tempat' }}
                                </span>
                                @if($booking->pembayaran?->bukti_transfer)
                                <a href="{{ route('admin.booking.bukti', $booking) }}" target="_blank" class="text-[11px] text-emerald-600 hover:text-emerald-800 hover:underline mt-1 inline-flex items-center gap-1 font-medium">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                    Lihat Bukti
                                </a>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <x-status-badge :status="$booking->status" />
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.booking.show', $booking) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $bookings->links() }}
            </div>
            @endif
        </div>
    </div>
</x:layouts.admin>
