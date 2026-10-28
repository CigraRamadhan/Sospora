<x:layouts.app>
    <x-slot:title>Notifikasi</x-slot:title>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-extrabold text-2xl text-slate-900">Notifikasi</h1>
            <form action="{{ route('user.notifikasi.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs text-primary-600 font-semibold hover:text-primary-800 transition">Tandai semua dibaca</button>
            </form>
        </div>

        @if($notifikasis->isEmpty())
            <x-empty-state icon="bell" title="Belum Ada Notifikasi" message="Semua notifikasi terkait booking Anda akan muncul di sini." />
        @else
        <div class="space-y-3">
            @foreach($notifikasis as $notif)
            <div class="bg-white rounded-2xl border {{ $notif->dibaca ? 'border-slate-100' : 'border-primary-200 bg-primary-50/30' }} p-4 shadow-sm flex items-start gap-3">
                {{-- Icon --}}
                <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center {{ $notif->tipe === 'booking_baru' ? 'bg-blue-100' : ($notif->tipe === 'pengingat' ? 'bg-amber-100' : 'bg-emerald-100') }}">
                    @if($notif->tipe === 'booking_baru')
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" /></svg>
                    @elseif($notif->tipe === 'pengingat')
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    @else
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-slate-800 leading-snug">{{ $notif->pesan }}</p>
                    <p class="text-xs text-slate-400 mt-1.5">{{ $notif->created_at->diffForHumans() }}</p>
                    @if($notif->booking_id)
                    <a href="{{ route('user.booking.show', $notif->booking->kode_booking) }}" class="inline-block mt-2 text-xs text-primary-600 font-semibold hover:text-primary-800">
                        Lihat Detail Booking →
                    </a>
                    @endif
                </div>

                {{-- Unread indicator --}}
                @if(!$notif->dibaca)
                <div class="w-2.5 h-2.5 rounded-full bg-primary-500 flex-shrink-0 mt-1"></div>
                @endif
            </div>
            @endforeach
        </div>

        @if($notifikasis->hasPages())
        <div class="mt-6">{{ $notifikasis->links() }}</div>
        @endif
        @endif
    </div>
</x:layouts.app>
