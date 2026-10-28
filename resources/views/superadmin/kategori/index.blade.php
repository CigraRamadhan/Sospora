<x:layouts.admin>
    <x-slot:title>Kelola Kategori Sarana</x-slot:title>

    <div class="p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-extrabold text-2xl text-slate-900">Kategori Sarana</h1>
            <button type="button" x-data x-on:click="$dispatch('open-confirm-modal', {})" onclick="document.getElementById('add-kategori-modal').classList.remove('hidden')" class="px-4 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition shadow-sm">
                + Tambah Kategori
            </button>
        </div>

        {{-- Add Modal --}}
        <div id="add-kategori-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
                <h3 class="font-heading font-bold text-lg text-slate-900 mb-4">Tambah Kategori Baru</h3>
                <form action="{{ route('superadmin.kategori.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_kategori" required placeholder="Contoh: Futsal" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Emoji Icon</label>
                        <input type="text" name="icon" placeholder="Contoh: ⚽" maxlength="10" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('add-kategori-modal').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Errors --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm mb-5">
            @foreach($errors->all() as $e) <p>• {{ $e }}</p> @endforeach
        </div>
        @endif

        {{-- Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($kategoris as $kategori)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="text-4xl mb-3">{{ $kategori->icon ?? '🏟️' }}</div>
                <h3 class="font-heading font-bold text-slate-800">{{ $kategori->nama_kategori }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ $kategori->saranas_count ?? 0 }} sarana • {{ $kategori->sarana_aktif_count ?? 0 }} aktif</p>

                <div class="flex items-center gap-2 mt-4">
                    <button
                        type="button"
                        onclick="openEditKategori({{ $kategori->id }}, '{{ $kategori->nama_kategori }}', '{{ $kategori->icon }}')"
                        class="flex-1 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
                    >Edit</button>
                    <form action="{{ route('superadmin.kategori.destroy', $kategori) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus kategori {{ $kategori->nama_kategori }}?')" class="px-3 py-2 rounded-xl border border-red-100 text-xs font-semibold text-red-600 hover:bg-red-50 transition">Hapus</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <x-empty-state icon="tag" title="Belum Ada Kategori" message="Tambah kategori sarana pertama." />
            </div>
            @endforelse
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="edit-kategori-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <h3 class="font-heading font-bold text-lg text-slate-900 mb-4">Edit Kategori</h3>
            <form id="edit-kategori-form" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kategori</label>
                    <input type="text" id="edit-nama" name="nama_kategori" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Emoji Icon</label>
                    <input type="text" id="edit-icon" name="icon" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('edit-kategori-modal').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-semibold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    function openEditKategori(id, nama, icon) {
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-icon').value = icon;
        document.getElementById('edit-kategori-form').action = `/superadmin/kategori/${id}`;
        document.getElementById('edit-kategori-modal').classList.remove('hidden');
    }
    </script>
    @endpush
</x:layouts.admin>
