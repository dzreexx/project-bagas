<!-- Backdrop untuk Mobile -->
<div id="sidebarBackdrop" class="fixed inset-0 z-20 bg-slate-900/50 backdrop-blur-xs hidden lg:hidden transition-opacity"></div>

<!-- Sidebar Utama -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 text-slate-300 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static lg:inset-0">
    
    <!-- Brand / Logo Gudang -->
    <div class="flex items-center justify-between h-16 px-6 bg-slate-950/50 border-b border-slate-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-white font-bold text-lg tracking-wide">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow-md shadow-indigo-600/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <span>LOGISTICS<span class="text-indigo-400">HUB</span></span>
        </a>
        <!-- Tombol Close di Mobile -->
        <button id="closeSidebar" class="lg:hidden text-slate-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Menu Navigasi -->
    <nav class="relative flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Menu Utama</p>

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           @class([
               'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-200 ease-out',
               'bg-indigo-600 text-white shadow-sm shadow-indigo-600/25' => request()->routeIs('dashboard'),
               'text-slate-400 hover:text-white hover:bg-slate-800/70' => !request()->routeIs('dashboard'),
           ])>
            <span @class([
                'w-1 rounded-full bg-indigo-200 transition-all duration-300 ease-out',
                'h-5 opacity-100' => request()->routeIs('dashboard'),
                'h-0 opacity-0 group-hover:h-3 group-hover:opacity-50 group-hover:bg-slate-400' => !request()->routeIs('dashboard'),
            ])></span>
            <svg class="w-5 h-5 shrink-0 transition-transform duration-200 group-hover:scale-105" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="transition-transform duration-200 group-hover:translate-x-0.5">Dashboard</span>
        </a>

        <!-- Data Barang / Stok -->
        <a href="{{ route('stok') }}"
           @class([
               'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-200 ease-out',
               'bg-indigo-600 text-white shadow-sm shadow-indigo-600/25' => request()->routeIs('stok*'),
               'text-slate-400 hover:text-white hover:bg-slate-800/70' => !request()->routeIs('stok*'),
           ])>
            <span @class([
                'w-1 rounded-full bg-indigo-200 transition-all duration-300 ease-out',
                'h-5 opacity-100' => request()->routeIs('stok*'),
                'h-0 opacity-0 group-hover:h-3 group-hover:opacity-50 group-hover:bg-slate-400' => !request()->routeIs('stok*'),
            ])></span>
            <svg class="w-5 h-5 shrink-0 transition-transform duration-200 group-hover:scale-105" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
            <span class="transition-transform duration-200 group-hover:translate-x-0.5">Stok &amp; Inventaris</span>
        </a>

        <!-- Barang Masuk (Inbound) -->
        <a href="{{ route('barang-masuk') }}"
           @class([
               'group relative flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition-all duration-200 ease-out',
               'bg-indigo-600 text-white shadow-sm shadow-indigo-600/25' => request()->routeIs('barang-masuk*'),
               'text-slate-400 hover:text-white hover:bg-slate-800/70' => !request()->routeIs('barang-masuk*'),
           ])>
            <div class="flex items-center gap-3">
                <span @class([
                    'w-1 rounded-full transition-all duration-300 ease-out',
                    'h-5 opacity-100 bg-indigo-200' => request()->routeIs('barang-masuk*'),
                    'h-0 opacity-0 bg-slate-400 group-hover:h-3 group-hover:opacity-50' => !request()->routeIs('barang-masuk*'),
                ])></span>
                <svg class="w-5 h-5 shrink-0 transition-transform duration-200 group-hover:scale-105" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
                <span class="transition-transform duration-200 group-hover:translate-x-0.5">Barang Masuk</span>
            </div>
            <span @class([
                'px-2 py-0.5 text-xs font-semibold rounded-full transition',
                'bg-white/20 text-white' => request()->routeIs('barang-masuk*'),
                'bg-emerald-500/10 text-emerald-400' => !request()->routeIs('barang-masuk*'),
            ])>12</span>
        </a>

        <!-- Barang Keluar (Outbound) -->
        <a href="{{ route('barang-keluar') }}"
           @class([
               'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-200 ease-out',
               'bg-indigo-600 text-white shadow-sm shadow-indigo-600/25' => request()->routeIs('barang-keluar*'),
               'text-slate-400 hover:text-white hover:bg-slate-800/70' => !request()->routeIs('barang-keluar*'),
           ])>
            <span @class([
                'w-1 rounded-full bg-indigo-200 transition-all duration-300 ease-out',
                'h-5 opacity-100' => request()->routeIs('barang-keluar*'),
                'h-0 opacity-0 group-hover:h-3 group-hover:opacity-50 group-hover:bg-slate-400' => !request()->routeIs('barang-keluar*'),
            ])></span>
            <svg class="w-5 h-5 shrink-0 transition-transform duration-200 group-hover:scale-105" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
            <span class="transition-transform duration-200 group-hover:translate-x-0.5">Barang Keluar</span>
        </a>

        <!-- Pengiriman / Armada -->
        <a href="{{ route('pengiriman') }}"
           @class([
               'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-200 ease-out',
               'bg-indigo-600 text-white shadow-sm shadow-indigo-600/25' => request()->routeIs('pengiriman*'),
               'text-slate-400 hover:text-white hover:bg-slate-800/70' => !request()->routeIs('pengiriman*'),
           ])>
            <span @class([
                'w-1 rounded-full bg-indigo-200 transition-all duration-300 ease-out',
                'h-5 opacity-100' => request()->routeIs('pengiriman*'),
                'h-0 opacity-0 group-hover:h-3 group-hover:opacity-50 group-hover:bg-slate-400' => !request()->routeIs('pengiriman*'),
            ])></span>
            <svg class="w-5 h-5 shrink-0 transition-transform duration-200 group-hover:scale-105" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
            <span class="transition-transform duration-200 group-hover:translate-x-0.5">Pengiriman &amp; Armada</span>
        </a>

        <!-- Kategori IT ACCESS -->
        <div class="pt-4 mt-4 border-t border-slate-800">
            <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">IT Access</p>

            <!-- User Administration -->
            <a href="{{ route('user-administration') }}"
               @class([
                   'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-200 ease-out',
                   'bg-indigo-600 text-white shadow-sm shadow-indigo-600/25' => request()->routeIs('user-administration*'),
                   'text-slate-400 hover:text-white hover:bg-slate-800/70' => !request()->routeIs('user-administration*'),
               ])>
                <span @class([
                    'w-1 rounded-full bg-indigo-200 transition-all duration-300 ease-out',
                    'h-5 opacity-100' => request()->routeIs('user-administration*'),
                    'h-0 opacity-0 group-hover:h-3 group-hover:opacity-50 group-hover:bg-slate-400' => !request()->routeIs('user-administration*'),
                ])></span>
                <svg class="w-5 h-5 shrink-0 transition-transform duration-200 group-hover:scale-105" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="transition-transform duration-200 group-hover:translate-x-0.5">User Administration</span>
            </a>
        </div>

        <div class="pt-4 mt-4 border-t border-slate-800">
            <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Administrasi</p>
            
            <!-- Laporan Gudang -->
            <a href="{{ route('laporan') }}"
               @class([
                   'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-200 ease-out',
                   'bg-indigo-600 text-white shadow-sm shadow-indigo-600/25' => request()->routeIs('laporan*'),
                   'text-slate-400 hover:text-white hover:bg-slate-800/70' => !request()->routeIs('laporan*'),
               ])>
                <span @class([
                    'w-1 rounded-full bg-indigo-200 transition-all duration-300 ease-out',
                    'h-5 opacity-100' => request()->routeIs('laporan*'),
                    'h-0 opacity-0 group-hover:h-3 group-hover:opacity-50 group-hover:bg-slate-400' => !request()->routeIs('laporan*'),
                ])></span>
                <svg class="w-5 h-5 shrink-0 transition-transform duration-200 group-hover:scale-105" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="transition-transform duration-200 group-hover:translate-x-0.5">Laporan Gudang</span>
            </a>

            <!-- Pengaturan -->
            <a href="{{ route('pengaturan') }}"
               @class([
                   'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all duration-200 ease-out',
                   'bg-indigo-600 text-white shadow-sm shadow-indigo-600/25' => request()->routeIs('pengaturan*'),
                   'text-slate-400 hover:text-white hover:bg-slate-800/70' => !request()->routeIs('pengaturan*'),
               ])>
                <span @class([
                    'w-1 rounded-full bg-indigo-200 transition-all duration-300 ease-out',
                    'h-5 opacity-100' => request()->routeIs('pengaturan*'),
                    'h-0 opacity-0 group-hover:h-3 group-hover:opacity-50 group-hover:bg-slate-400' => !request()->routeIs('pengaturan*'),
                ])></span>
                <svg class="w-5 h-5 shrink-0 transition-transform duration-200 group-hover:scale-105" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="transition-transform duration-200 group-hover:translate-x-0.5">Pengaturan</span>
            </a>
        </div>
    </nav>

    <!-- Profil User di Bagian Bawah Sidebar -->
    @php
        $authUser = auth()->user();
        $userName = $authUser?->name ?? 'Tamu';
        $userRole = $authUser?->role ?? 'Pengguna';
        $userInitials = collect(explode(' ', trim($userName)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->implode('') ?: 'U';
    @endphp
    <div class="p-4 border-t border-slate-800 bg-slate-950/30">
        <div class="flex items-center justify-between gap-2">
            <!-- Klik profil mengarah ke halaman Pengaturan -->
            <a href="{{ route('pengaturan') }}" title="Pengaturan Profil" class="flex items-center gap-3 flex-1 min-w-0 rounded-xl p-1 -m-1 hover:bg-slate-800/60 transition">
                <div class="w-9 h-9 rounded-full bg-indigo-500/20 border border-indigo-500/40 flex items-center justify-center text-indigo-400 font-bold text-sm shrink-0">
                    {{ $userInitials }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $userName }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ $userRole }}</p>
                </div>
            </a>

            <!-- Form Logout Aktif -->
            <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit" title="Keluar" class="p-2 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const activeIndicator = document.querySelector('nav a.bg-indigo-600 span.rounded-full');
        if (activeIndicator) {
            activeIndicator.style.height = '4px';
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    activeIndicator.style.height = '20px';
                });
            });
        }
    });
</script>