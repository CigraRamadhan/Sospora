<x:layouts.admin>
    <x-slot:title>Kelola Admin</x-slot:title>

    <div class="p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="font-heading font-extrabold text-2xl text-slate-900">Akun Admin</h1>
            <a href="{{ route('superadmin.admin.create') }}" class="px-4 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition shadow-sm">
                + Tambah Admin
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            @if($admins->isEmpty())
            <div class="py-12">
                <x-empty-state icon="users" title="Belum Ada Admin" message="Tambahkan akun admin pertama." :action-href="route('superadmin.admin.create')" action-label="Tambah Admin" />
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Admin</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider hidden md:table-cell">No. HP</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider hidden lg:table-cell">Total Verifikasi</th>
                            <th class="text-left px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-right px-5 py-3 font-semibold text-xs text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($admins as $admin)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-primary-600 text-white font-bold flex items-center justify-center text-sm flex-shrink-0">
                                        {{ strtoupper(substr($admin->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $admin->nama }}</p>
                                        <p class="text-xs text-slate-500">{{ $admin->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 hidden md:table-cell text-xs text-slate-600">{{ $admin->no_hp }}</td>
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <span class="font-semibold text-slate-800">{{ $admin->verified_bookings_count ?? 0 }}</span>
                                <span class="text-xs text-slate-400 ml-1">booking</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $admin->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                    {{ ucfirst($admin->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('superadmin.admin.edit', $admin) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">Edit</a>
                                    <form action="{{ route('superadmin.admin.toggle-status', $admin) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold border {{ $admin->status === 'aktif' ? 'border-amber-200 text-amber-700 hover:bg-amber-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }} transition">
                                            {{ $admin->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $admins->links() }}
            </div>
            @endif
        </div>
    </div>
</x:layouts.admin>
