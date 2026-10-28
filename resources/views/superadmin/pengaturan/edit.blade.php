<x:layouts.admin>
    <x-slot:title>Pengaturan Sistem</x-slot:title>

    <div class="p-6 lg:p-8 max-w-2xl">
        <h1 class="font-heading font-extrabold text-2xl text-slate-900 mb-2">Pengaturan Sistem</h1>
        <p class="text-slate-500 text-sm mb-8">Konfigurasi informasi rekening, kontak, dan detail operasional Dispora.</p>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm mb-5">
            @foreach($errors->all() as $e) <p>• {{ $e }}</p> @endforeach
        </div>
        @endif

        <form action="{{ route('superadmin.pengaturan.update') }}" method="POST">
            @csrf @method('PUT')

            {{-- Rekening Transfer --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                    </div>
                    <div>
                        <h2 class="font-heading font-bold text-slate-800">Rekening Pembayaran</h2>
                        <p class="text-xs text-slate-500">Ditampilkan kepada warga saat melakukan transfer</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Bank <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_bank" value="{{ old('nama_bank', $pengaturan->nama_bank) }}" placeholder="Contoh: Bank Jawa Barat (BJB)" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 @error('nama_bank') border-red-400 @enderror">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Rekening <span class="text-red-500">*</span></label>
                            <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $pengaturan->nomor_rekening) }}" placeholder="Contoh: 0123456789" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 @error('nomor_rekening') border-red-400 @enderror">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Atas Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="atas_nama" value="{{ old('atas_nama', $pengaturan->atas_nama) }}" placeholder="Contoh: Dinas Pemuda dan Olahraga Kota Bandung" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 @error('atas_nama') border-red-400 @enderror">
                    </div>
                </div>
            </div>

            {{-- Kontak & Operasional --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                    </div>
                    <div>
                        <h2 class="font-heading font-bold text-slate-800">Kontak & Operasional</h2>
                        <p class="text-xs text-slate-500">Tampil di footer website publik</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Kontak <span class="text-red-500">*</span></label>
                            <input type="email" name="kontak_email" value="{{ old('kontak_email', $pengaturan->kontak_email) }}" placeholder="dispora@bandung.go.id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="kontak_telepon" value="{{ old('kontak_telepon', $pengaturan->kontak_telepon) }}" placeholder="(022) 4203366" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Kantor <span class="text-red-500">*</span></label>
                        <textarea name="alamat_kantor" rows="2" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none">{{ old('alamat_kantor', $pengaturan->alamat_kantor) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jam Layanan <span class="text-red-500">*</span></label>
                        <input type="text" name="jam_layanan" value="{{ old('jam_layanan', $pengaturan->jam_layanan) }}" placeholder="Senin–Jumat, 08.00–16.00 WIB" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm transition shadow-md shadow-primary-500/20 hover:scale-[1.01] active:scale-[0.99]">
                Simpan Pengaturan
            </button>
        </form>
    </div>
</x:layouts.admin>
