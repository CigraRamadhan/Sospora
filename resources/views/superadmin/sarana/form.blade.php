@php $isEdit = isset($sarana); $action = $isEdit ? route('superadmin.sarana.update', $sarana) : route('superadmin.sarana.store'); @endphp
<x:layouts.admin>
    <x-slot:title>{{ $isEdit ? 'Edit' : 'Tambah' }} Sarana</x-slot:title>

    <div class="p-6 lg:p-8 max-w-3xl">
        <a href="{{ route('superadmin.sarana.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-primary-600 mb-6 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            Kembali
        </a>

        <h1 class="font-heading font-extrabold text-2xl text-slate-900 mb-6">
            {{ $isEdit ? 'Edit Sarana: ' . $sarana->nama_sarana : 'Tambah Sarana Baru' }}
        </h1>

        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($isEdit) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm mb-5">
                @foreach($errors->all() as $e) <p>• {{ $e }}</p> @endforeach
            </div>
            @endif

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
                {{-- Kategori --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 @error('kategori_id') border-red-400 @enderror">
                            @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id', $isEdit ? $sarana->kategori_id : '') == $k->id ? 'selected' : '' }}>
                                {{ $k->icon }} {{ $k->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                        <select name="status" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                            <option value="aktif" {{ old('status', $isEdit ? $sarana->status : 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $isEdit ? $sarana->status : '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Sarana <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_sarana" value="{{ old('nama_sarana', $isEdit ? $sarana->nama_sarana : '') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 @error('nama_sarana') border-red-400 @enderror">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $isEdit ? $sarana->lokasi : '') }}" required placeholder="Alamat lengkap sarana" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 @error('lokasi') border-red-400 @enderror">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" rows="4" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none @error('deskripsi') border-red-400 @enderror">{{ old('deskripsi', $isEdit ? $sarana->deskripsi : '') }}</textarea>
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jam Buka <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_buka" value="{{ old('jam_buka', $isEdit ? substr($sarana->jam_buka, 0, 5) : '07:00') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jam Tutup <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_tutup" value="{{ old('jam_tutup', $isEdit ? substr($sarana->jam_tutup, 0, 5) : '22:00') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Interval Slot</label>
                        <select name="interval_slot_menit" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                            @foreach([30 => '30 menit', 60 => '60 menit (1 jam)', 90 => '90 menit', 120 => '120 menit (2 jam)'] as $val => $label)
                            <option value="{{ $val }}" {{ old('interval_slot_menit', $isEdit ? $sarana->interval_slot_menit : 60) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Harga per Slot (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_per_slot" value="{{ old('harga_per_slot', $isEdit ? $sarana->harga_per_slot : '') }}" min="0" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 @error('harga_per_slot') border-red-400 @enderror">
                </div>

                {{-- Photos --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Foto Sarana
                        <span class="text-slate-400 font-normal text-xs">(max 3MB per file)</span>
                    </label>

                    @if($isEdit && $sarana->fotos->isNotEmpty())
                    <div class="flex gap-2 flex-wrap mb-3">
                        @foreach($sarana->fotos as $foto)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $foto->path_file) }}" class="w-20 h-20 rounded-xl object-cover border border-slate-200">
                            <form action="{{ route('superadmin.sarana.foto.destroy', [$sarana, $foto]) }}" method="POST" class="absolute inset-0 hidden group-hover:flex items-center justify-center bg-black/50 rounded-xl">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus foto ini?')" class="w-6 h-6 rounded-full bg-red-600 text-white text-xs flex items-center justify-center">✕</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <input type="file" name="fotos[]" accept="image/*" multiple class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                    @error('fotos.*') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <a href="{{ route('superadmin.sarana.index') }}" class="flex-1 text-center py-3.5 rounded-2xl border border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="flex-1 py-3.5 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm transition shadow-md">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Sarana' }}
                </button>
            </div>
        </form>
    </div>
</x:layouts.admin>
