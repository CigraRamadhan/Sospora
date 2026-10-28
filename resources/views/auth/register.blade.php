<x-layouts.app title="Daftar Akun Warga">
    <div class="min-h-[calc(100vh-14rem)] flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-600 to-primary-900 text-white shadow-lg shadow-primary-500/20 mb-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-heading font-bold text-slate-900 tracking-tight">
                Pendaftaran Akun Warga
            </h2>
            <p class="mt-2 text-sm text-slate-500">
                Buat akun untuk booking sarana olahraga Dispora Kota Bandung dengan mudah
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 sm:px-10 shadow-xl shadow-slate-200/50 rounded-2xl border border-slate-100">
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="nama" class="block text-sm font-medium text-slate-700 mb-1">
                            Nama Lengkap
                        </label>
                        <input
                            id="nama"
                            name="nama"
                            type="text"
                            autocomplete="name"
                            required
                            value="{{ old('nama') }}"
                            class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                            placeholder="Contoh: Budi Santoso"
                        >
                        @error('nama')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                            Alamat Email
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            value="{{ old('email') }}"
                            class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                            placeholder="nama@email.com"
                        >
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-slate-700 mb-1">
                            Nomor WhatsApp / HP Aktif
                        </label>
                        <input
                            id="no_hp"
                            name="no_hp"
                            type="tel"
                            autocomplete="tel"
                            required
                            value="{{ old('no_hp') }}"
                            class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                            placeholder="081234567890"
                        >
                        @error('no_hp')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                            Kata Sandi (Password)
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                            placeholder="Minimal 8 karakter"
                        >
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">
                            Konfirmasi Kata Sandi
                        </label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                            placeholder="Ulangi kata sandi"
                        >
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full flex justify-center py-3 px-4 rounded-xl text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-md shadow-primary-500/25 transition cursor-pointer"
                        >
                            Daftar Akun Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-6 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-500">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700 transition">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
