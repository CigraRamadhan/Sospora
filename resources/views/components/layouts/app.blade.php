<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — SOSPORA Kota Bandung' : 'SOSPORA — Sarana Olahraga Dispora Kota Bandung' }}</title>
    <meta name="description" content="Digitalisasi Sistem Peminjaman dan Booking Sarana Olahraga Milik Dinas Pemuda dan Olahraga (Dispora) Kota Bandung.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full flex flex-col font-sans text-slate-800 bg-slate-50 selection:bg-primary-500 selection:text-white">

    <!-- Global Toasts -->
    <x-toast />

    <!-- Global Confirm Modal -->
    <x-confirm-modal />

    <!-- Header / Navbar -->
    <header
        x-data="{
            mobileOpen: false,
            unreadCount: {{ Auth::check() ? Auth::user()->notifikasi()->where('dibaca', false)->count() : 0 }},
            pollNotifications() {
                @if(Auth::check())
                @php
                    $notifCountUrl = Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()
                        ? route('admin.notifikasi.unread-count')
                        : route('user.notifikasi.unread-count');
                @endphp
                fetch('{{ $notifCountUrl }}')
                    .then(res => res.json())
                    .then(data => { if (data && typeof data.count !== 'undefined') this.unreadCount = data.count; })
                    .catch(() => {});
                @endif
            }
        }"
        x-init="
            @if(Auth::check())
            setInterval(() => pollNotifications(), 30000);
            @endif
        "
        class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.03)]"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-gradient-to-br from-primary-600 to-primary-900 flex items-center justify-center text-white shadow-md shadow-primary-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.982-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.483 2.244 9.81 2 12 2c2.19 0 4.517.244 6.75.72v1.516M7.73 9.728a6.726 6.726 0 002.77 1.35m8.25-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.77 1.35m0 0a3.73 3.73 0 01-1.61 0" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-heading font-extrabold text-xl sm:text-2xl tracking-tight text-slate-900 block leading-none">
                            SOSPORA
                        </span>
                        <span class="text-[10px] sm:text-xs font-medium text-slate-500 tracking-wider uppercase block mt-1">
                            Dispora Kota Bandung
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-1 sm:gap-2">
                    <a
                        href="{{ route('home') }}"
                        class="px-3.5 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-600 hover:text-primary-600 hover:bg-slate-50' }}"
                    >
                        Beranda
                    </a>
                    <a
                        href="{{ route('sarana.index') }}"
                        class="px-3.5 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('sarana.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-600 hover:text-primary-600 hover:bg-slate-50' }}"
                    >
                        Cari Sarana
                    </a>

                    @auth
                        @if(Auth::user()->isUser())
                            <a
                                href="{{ route('user.dashboard') }}"
                                class="px-3.5 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('user.dashboard') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-600 hover:text-primary-600 hover:bg-slate-50' }}"
                            >
                                Dashboard
                            </a>
                            <a
                                href="{{ route('user.booking.index') }}"
                                class="px-3.5 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('user.booking.index') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-600 hover:text-primary-600 hover:bg-slate-50' }}"
                            >
                                Booking Saya
                            </a>
                            <a
                                href="{{ route('user.booking.riwayat') }}"
                                class="px-3.5 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('user.booking.riwayat') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-600 hover:text-primary-600 hover:bg-slate-50' }}"
                            >
                                Riwayat
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- Right Action Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <!-- Staff Portal Shortcut -->
                        @if(Auth::user()->isSuperAdmin())
                            <a
                                href="{{ route('superadmin.dashboard') }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 text-amber-700 text-xs font-semibold hover:bg-amber-500/20 transition"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                                </svg>
                                <span>Panel Super Admin</span>
                            </a>
                        @elseif(Auth::user()->isAdmin())
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-primary-900 text-white text-xs font-semibold hover:bg-primary-800 transition"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                </svg>
                                <span>Panel Admin</span>
                            </a>
                        @endif

                        <!-- Notification Bell with Polling Badge -->
                        <a
                            href="{{ Auth::user()->isAdmin() || Auth::user()->isSuperAdmin() ? route('admin.notifikasi.index') : route('user.notifikasi.index') }}"
                            class="relative p-2 rounded-xl text-slate-500 hover:text-primary-600 hover:bg-slate-100 transition"
                            title="Notifikasi"
                        >
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            <span
                                x-show="unreadCount > 0"
                                x-text="unreadCount > 99 ? '99+' : unreadCount"
                                class="absolute top-1 right-1 flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full text-[10px] font-bold bg-red-600 text-white shadow-sm ring-2 ring-white"
                                style="display: none;"
                            ></span>
                        </a>

                        <!-- User Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative" x-on:click.outside="open = false">
                            <button
                                type="button"
                                x-on:click="open = !open"
                                class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer"
                            >
                                <div class="w-9 h-9 rounded-xl bg-primary-600 text-white font-semibold flex items-center justify-center text-sm shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->nama ?? 'U', 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-slate-700 max-w-[120px] truncate">
                                    {{ Auth::user()->nama }}
                                </span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                x-show="open"
                                x-transition:enter="ease-out duration-150"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-slate-100 py-2 z-50"
                                style="display: none;"
                            >
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-xs text-slate-400 font-medium">Masuk sebagai</p>
                                    <p class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->nama }}</p>
                                    <span class="inline-block mt-1 text-[11px] px-2 py-0.5 rounded-md font-medium capitalize {{ Auth::user()->isSuperAdmin() ? 'bg-amber-100 text-amber-800' : (Auth::user()->isAdmin() ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800') }}">
                                        Role: {{ Auth::user()->role }}
                                    </span>
                                </div>

                                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                    </svg>
                                    Dashboard Saya
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="px-4 py-2 rounded-xl text-sm font-medium text-slate-700 hover:text-primary-600 hover:bg-slate-50 transition"
                        >
                            Masuk
                        </a>
                        <a
                            href="{{ route('register') }}"
                            class="px-4 py-2.5 rounded-xl text-sm font-medium bg-primary-600 text-white hover:bg-primary-700 transition shadow-sm shadow-primary-500/20"
                        >
                            Daftar Akun
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="flex items-center gap-2 md:hidden">
                    @auth
                        <a
                            href="{{ Auth::user()->isAdmin() || Auth::user()->isSuperAdmin() ? route('admin.notifikasi.index') : route('user.notifikasi.index') }}"
                            class="relative p-2 rounded-xl text-slate-500 hover:text-primary-600 hover:bg-slate-100 transition"
                        >
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            <span
                                x-show="unreadCount > 0"
                                x-text="unreadCount"
                                class="absolute top-1 right-1 flex items-center justify-center min-w-[16px] h-[16px] px-1 rounded-full text-[9px] font-bold bg-red-600 text-white"
                                style="display: none;"
                            ></span>
                        </a>
                    @endauth

                    <button
                        type="button"
                        x-on:click="mobileOpen = !mobileOpen"
                        class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition cursor-pointer"
                        aria-label="Toggle menu"
                    >
                        <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg x-show="mobileOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden border-b border-slate-200 bg-white px-4 pt-2 pb-6 space-y-3"
            style="display: none;"
        >
            <div class="space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-base font-medium {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    Beranda
                </a>
                <a href="{{ route('sarana.index') }}" class="block px-3 py-2 rounded-xl text-base font-medium {{ request()->routeIs('sarana.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    Cari Sarana
                </a>

                @auth
                    @if(Auth::user()->isUser())
                        <a href="{{ route('user.dashboard') }}" class="block px-3 py-2 rounded-xl text-base font-medium {{ request()->routeIs('user.dashboard') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                            Dashboard Saya
                        </a>
                        <a href="{{ route('user.booking.index') }}" class="block px-3 py-2 rounded-xl text-base font-medium {{ request()->routeIs('user.booking.index') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                            Booking Saya
                        </a>
                        <a href="{{ route('user.booking.riwayat') }}" class="block px-3 py-2 rounded-xl text-base font-medium {{ request()->routeIs('user.booking.riwayat') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                            Riwayat Peminjaman
                        </a>
                        <a href="{{ route('user.notifikasi.index') }}" class="block px-3 py-2 rounded-xl text-base font-medium {{ request()->routeIs('user.notifikasi.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                            Notifikasi (<span x-text="unreadCount"></span>)
                        </a>
                    @endif

                    @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                        <div class="pt-2 border-t border-slate-100">
                            <a href="{{ Auth::user()->isSuperAdmin() ? route('superadmin.dashboard') : route('admin.dashboard') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-primary-700 bg-primary-50">
                                Masuk ke Panel {{ Auth::user()->isSuperAdmin() ? 'Super Admin' : 'Admin' }}
                            </a>
                        </div>
                    @endif
                @endauth
            </div>

            <div class="pt-4 border-t border-slate-100">
                @auth
                    <div class="flex items-center justify-between px-3 py-2">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->nama }}</p>
                            <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100">
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-medium text-slate-700">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center justify-center px-4 py-2.5 rounded-xl bg-primary-600 text-sm font-medium text-white shadow-sm">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    @php
        $settings = \App\Models\PengaturanSistem::first();
    @endphp
    <footer class="bg-primary-900 text-slate-300 border-t border-primary-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 lg:gap-12">
                <!-- Col 1: Brand & About -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                            S
                        </div>
                        <span class="font-heading font-extrabold text-2xl text-white tracking-tight">SOSPORA</span>
                    </div>
                    <p class="text-sm text-slate-300 leading-relaxed max-w-md">
                        Sistem Informasi Sarana Olahraga Dispora Kota Bandung — platform resmi peminjaman fasilitas dan sarana olahraga publik untuk mendukung gaya hidup sehat dan prestasi masyarakat Kota Bandung.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Layanan Resmi Terintegrasi
                        </span>
                    </div>
                </div>

                <!-- Col 2: Navigation Links -->
                <div class="space-y-4">
                    <h4 class="font-heading font-semibold text-white text-base">Tautan Pintas</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="{{ route('sarana.index') }}" class="hover:text-white transition">Katalog Sarana</a></li>
                        @auth
                            <li><a href="{{ route('user.dashboard') }}" class="hover:text-white transition">Dashboard Warga</a></li>
                            <li><a href="{{ route('user.booking.index') }}" class="hover:text-white transition">Cek Status Booking</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition">Masuk Akun</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition">Registrasi Akun Baru</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Col 3: Contact Info -->
                <div class="space-y-4">
                    <h4 class="font-heading font-semibold text-white text-base">Kantor Dispora Bandung</h4>
                    <div class="space-y-3 text-sm text-slate-300">
                        <div class="flex items-start gap-2.5">
                            <svg class="w-5 h-5 text-primary-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <span>{{ $settings->alamat_kantor ?? 'Jl. Tamansari No. 76, Kota Bandung' }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-primary-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            <span>{{ $settings->kontak_telepon ?? '(022) 4203366' }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-primary-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            <span>{{ $settings->kontak_email ?? 'dispora@bandung.go.id' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="mt-12 pt-8 border-t border-primary-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} Dinas Pemuda dan Olahraga Pemerintah Kota Bandung. Hak cipta dilindungi.</p>
                <div class="flex items-center gap-4">
                    <span>Versi 1.0.0</span>
                    <span>•</span>
                    <span class="text-primary-400 font-medium">Kota Bandung Juara</span>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
