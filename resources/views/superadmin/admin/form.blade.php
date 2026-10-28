@php $isEdit = isset($admin); $action = $isEdit ? route('superadmin.admin.update', $admin) : route('superadmin.admin.store'); @endphp
<x:layouts.admin>
    <x-slot:title>{{ $isEdit ? 'Edit' : 'Tambah' }} Admin</x-slot:title>

    <div class="p-6 lg:p-8 max-w-lg">
        <a href="{{ route('superadmin.admin.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-primary-600 mb-6 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            Kembali
        </a>

        <h1 class="font-heading font-extrabold text-2xl text-slate-900 mb-6">{{ $isEdit ? 'Edit Admin: ' . $admin->nama : 'Tambah Admin Baru' }}</h1>

        <form action="{{ $action }}" method="POST">
            @csrf
            @if($isEdit) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm mb-5">
                @foreach($errors->all() as $e) <p>• {{ $e }}</p> @endforeach
            </div>
            @endif

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $isEdit ? $admin->nama : '') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $isEdit ? $admin->email : '') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. HP <span class="text-red-500">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $isEdit ? $admin->no_hp : '') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password {{ $isEdit ? '(kosongkan jika tidak diubah)' : '' }} <span class="text-red-500">{{ $isEdit ? '' : '*' }}</span></label>
                    <input type="password" name="password" {{ $isEdit ? '' : 'required' }} class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <a href="{{ route('superadmin.admin.index') }}" class="flex-1 text-center py-3.5 rounded-2xl border border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="flex-1 py-3.5 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm transition shadow-md">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Buat Akun Admin' }}
                </button>
            </div>
        </form>
    </div>
</x:layouts.admin>
