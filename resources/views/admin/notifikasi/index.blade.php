<x:layouts.admin>
    <x-slot:title>Notifikasi</x-slot:title>

    <div class="p-6 lg:p-8 max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-extrabold text-2xl text-slate-900">Notifikasi</h1>
        </div>

        @if($notifikasis->isEmpty())
            <x-empty-state icon="bell" title="Tidak Ada Notifikasi" message="Notifikasi terkait booking baru akan muncul di sini." />
        @else
        <div class="space-y-3">
            @foreach($notifikasis as $notif)
            <div class="bg-white rounded-2xl border {{ $notif->dibaca ? 'border-slate-100' : 'border-primary-200' }} p-4 shadow-sm flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center bg-blue-100">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" /></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-slate-800">{{ $notif->pesan }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    @if($notif->booking_id)
                    <a href="{{ route('admin.booking.show', $notif->booking) }}" class="inline-block mt-2 text-xs text-primary-600 font-semibold hover:text-primary-800">
                        Tinjau Booking →
                    </a>
                    @endif
                </div>
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
</x:layouts.admin>
