<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — Panel ' . (Auth::user()->isSuperAdmin() ? 'Super Admin' : 'Admin') : 'Panel Kelola SOSPORA' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body
    x-data="{
        sidebarOpen: false,
        unreadCount: {{ Auth::user()->notifikasi()->where('dibaca', false)->count() }},
        pollNotifications() {
            fetch('{{ route('admin.notifikasi.unread-count') }}')
                .then(res => res.json())
                .then(data => { if (data && typeof data.count !== 'undefined') this.unreadCount = data.count; })
                .catch(() => {});
        }
    }"
    x-init="setInterval(() => pollNotifications(), 30000)"
    class="min-h-full flex font-sans text-slate-800 bg-slate-100"
>
    <!-- Global Toasts -->
    <x-toast />

    <!-- Global Confirm Modal -->
    <x-confirm-modal />

    <!-- Off-canvas Mobile Sidebar Backdrop -->
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
        x-on:click="sidebarOpen = false"
        style="display: none;"
    ></div>

    <!-- Sidebar (Shared between Desktop & Mobile) -->
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-primary-900 text-white flex flex-col transition-transform duration-300 ease-in-out shadow-xl lg:shadow-none"
    >
        <!-- Brand Header -->
        <div class="h-16 sm:h-20 flex items-center justify-between px-6 border-b border-primary-800/80">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-primary-600 flex items-center justify-center font-bold text-white shadow-md">
                    S
                </div>
                <div>
                    <span class="font-heading font-bold text-lg text-white tracking-tight block leading-none">
                        SOSPORA
                    </span>
                    <span class="text-[10px] uppercase font-semibold text-primary-300 tracking-wider">
                        {{ Auth::user()->isSuperAdmin() ? 'Super Admin Portal' : 'Admin Operasional' }}
                    </span>
                </div>
            </a>

            <!-- Mobile Close Button -->
            <button
                type="button"
                x-on:click="sidebarOpen = false"
                class="lg:hidden p-1.5 rounded-lg text-primary-300 hover:text-white hover:bg-primary-800 transition"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5">
            <div class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-primary-400">
                Menu Utama
            </div>

            @if(Auth::user()->isSuperAdmin())
                <!-- Super Admin Links -->
                <a
                    href="{{ route('superadmin.dashboard') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('superadmin.dashboard') ? 'bg-primary-800 text-white font-semibold shadow-inner' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a
                    href="{{ route('admin.booking.index') }}"
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.booking.*') ? 'bg-primary-800 text-white font-semibold shadow-inner' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }}"
                >
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <span>Kelola Booking</span>
                    </div>
                    @php
                        $pendingCount = \App\Models\Booking::whereIn('status', ['menunggu_konfirmasi', 'menunggu_pembayaran'])->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500 text-white">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <a
                    href="{{ route('superadmin.sarana.index') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('superadmin.sarana.*') ? 'bg-primary-800 text-white font-semibold shadow-inner' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.75c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                    </svg>
                    <span>Sarana Olahraga</span>
                </a>

                <a
                    href="{{ route('superadmin.kategori.index') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('superadmin.kategori.*') ? 'bg-primary-800 text-white font-semibold shadow-inner' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.386a10.967 10.967 0 003.743-3.743c.486-.827.313-1.908-.386-2.607L9.568 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                    </svg>
                    <span>Kategori Sarana</span>
                </a>

                <a
                    href="{{ route('superadmin.admin.index') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('superadmin.admin.*') ? 'bg-primary-800 text-white font-semibold shadow-inner' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span>Kelola Admin</span>
                </a>

                <div class="pt-4 px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-primary-400">
                    Sistem & Laporan
                </div>

                <a
                    href="{{ route('superadmin.pengaturan.edit') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('superadmin.pengaturan.*') ? 'bg-primary-800 text-white font-semibold shadow-inner' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                    </svg>
                    <span>Pengaturan Rekening</span>
                </a>

                <a
                    href="{{ route('superadmin.laporan.index') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('superadmin.laporan.*') ? 'bg-primary-800 text-white font-semibold shadow-inner' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <span>Laporan & Rekap</span>
                </a>
            @else
                <!-- Admin Operasional Links -->
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-primary-800 text-white font-semibold shadow-inner' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    <span>Dashboard Operasional</span>
                </a>

                <a
                    href="{{ route('admin.booking.index') }}"
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.booking.*') ? 'bg-primary-800 text-white font-semibold shadow-inner' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }}"
                >
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <span>Kelola Booking</span>
                    </div>
                    @php
                        $pendingCount = \App\Models\Booking::whereIn('status', ['menunggu_konfirmasi', 'menunggu_pembayaran'])->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500 text-white">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
            @endif

            <div class="pt-4 px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-primary-400">
                Pemberitahuan
            </div>

            <a
                href="{{ route('admin.notifikasi.index') }}"
                class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.notifikasi.*') ? 'bg-primary-800 text-white font-semibold shadow-inner' : 'text-primary-100 hover:bg-primary-800/50 hover:text-white' }}"
            >
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span>Notifikasi Admin</span>
                </div>
                <span
                    x-show="unreadCount > 0"
                    x-text="unreadCount"
                    class="px-2 py-0.5 rounded-full text-xs font-bold bg-red-600 text-white"
                    style="display: none;"
                ></span>
            </a>
        </div>

        <!-- Sidebar Footer User Profile -->
        <div class="p-4 border-t border-primary-800/80 bg-primary-950/40">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-primary-700 text-white font-bold flex items-center justify-center text-sm flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->nama ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->nama }}</p>
                        <p class="text-xs text-primary-300 capitalize truncate">{{ Auth::user()->role }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 text-primary-300 hover:text-white rounded-lg hover:bg-primary-800 transition" title="Keluar">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Container -->
    <div class="lg:pl-64 flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-16 sm:h-20 bg-white border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    x-on:click="sidebarOpen = true"
                    class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition"
                    aria-label="Buka menu"
                >
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Breadcrumb Title -->
                <div>
                    <h1 class="text-base sm:text-lg font-heading font-bold text-slate-900 leading-tight">
                        {{ $heading ?? 'Dashboard' }}
                    </h1>
                    @if(isset($subheading))
                        <p class="text-xs text-slate-500 hidden sm:block">{{ $subheading }}</p>
                    @endif
                </div>
            </div>

            <!-- Right Topbar Actions -->
            <div class="flex items-center gap-2 sm:gap-3">
                <a
                    href="{{ route('home') }}"
                    target="_blank"
                    class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                    <span>Lihat Web Publik</span>
                </a>

                <!-- Notification Bell -->
                <a
                    href="{{ route('admin.notifikasi.index') }}"
                    class="relative p-2 rounded-xl text-slate-500 hover:text-primary-600 hover:bg-slate-100 transition"
                    title="Notifikasi"
                >
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span
                        x-show="unreadCount > 0"
                        x-text="unreadCount"
                        class="absolute top-1.5 right-1.5 flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full text-[10px] font-bold bg-red-600 text-white shadow-sm ring-2 ring-white"
                        style="display: none;"
                    ></span>
                </a>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
