<x:layouts.admin>
    <x-slot:title>Kelola Sarana Olahraga</x-slot:title>

    <div class="p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-extrabold text-2xl text-slate-900">Sarana Olahraga</h1>
            <a href="{{ route('superadmin.sarana.create') }}" class="px-4 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition shadow-sm">
                + Tambah Sarana
            </a>
        </div>

        {{-- Filters --}}
        <form action="{{ route('superadmin.sarana.index') }}" method="GET" class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm mb-6">
            <div class="flex flex-wrap gap-3">
                <select name="kategori" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="dihapus" {{ request('status') === 'dihapus' ? 'selected' : '' }}>Dihapus</option>
                </select>
                @if(request()->hasAny(['kategori', 'status']))
                <a href="{{ route('superadmin.sarana.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50">Reset</a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            @if($sarana->isEmpty())
            <div class="py-12">
                <x-empty-state icon="building" title="Belum Ada Sarana" message="Tambahkan sarana olahraga pertama." :action-href="route('superadmin.sarana.create')" action-label="Tambah Sarana" />
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Sarana</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider hidden md:table-cell">Kategori</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider hidden lg:table-cell">Jadwal & Harga</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-right px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($sarana as $item)
                        <tr class="hover:bg-slate-50/50 transition {{ $item->trashed() ? 'opacity-50' : '' }}">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800">{{ $item->nama_sarana }}</p>
                                <p class="text-xs text-slate-500 mt-0.5 truncate max-w-[200px]">{{ $item->lokasi }}</p>
                            </td>
                            <td class="px-5 py-4 hidden md:table-cell">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium">
                                    {{ $item->kategori->icon ?? '' }} {{ $item->kategori->nama_kategori }}
                                </span>
                            </td>
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <p class="text-xs text-slate-700">{{ substr($item->jam_buka, 0, 5) }} – {{ substr($item->jam_tutup, 0, 5) }} • {{ $item->interval_slot_menit }}m</p>
                                <p class="text-xs font-bold text-primary-700 mt-0.5">Rp {{ number_format($item->harga_per_slot, 0, ',', '.') }}/slot</p>
                            </td>
                            <td class="px-5 py-4">
                                @if($item->trashed())
                                    <span class="px-2 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-600">Dihapus</span>
                                @else
                                    <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $item->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$item->trashed())
                                    <a href="{{ route('superadmin.sarana.edit', $item) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">Edit</a>
                                    <form action="{{ route('superadmin.sarana.toggle-status', $item) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold border {{ $item->status === 'aktif' ? 'border-amber-200 text-amber-700 hover:bg-amber-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }} transition">
                                            {{ $item->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('superadmin.sarana.destroy', $item) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus sarana {{ $item->nama_sarana }}?')" class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-red-100 text-red-600 hover:bg-red-50 transition">Hapus</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $sarana->links() }}
            </div>
            @endif
        </div>
    </div>
</x:layouts.admin>
