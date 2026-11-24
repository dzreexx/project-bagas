@php
    $prefUser = auth()->user();
    $themeMode = $prefUser?->theme_mode ?? 'light';
    $fontSize = $prefUser?->font_size ?? 'base';
    $compactMode = (bool) ($prefUser?->compact_mode ?? false);

    $fontClass = match($fontSize) {
        'sm' => 'text-sm',
        'lg' => 'text-base',
        default => 'text-[15px]',
    };
@endphp
<!DOCTYPE html>
<html lang="id" class="{{ $themeMode === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Logistics Hub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="{{ $fontClass }} {{ $themeMode === 'dark' ? 'bg-slate-950 text-slate-100' : 'bg-slate-100 text-slate-800' }} antialiased {{ $compactMode ? 'compact-ui' : '' }}">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Memanggil View Sidebar Terpisah -->
        @include('layouts.sidebar')

        <!-- Wrapper Konten Utama -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Header -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 z-10">
                <div class="flex items-center gap-3">
                    <!-- Tombol Hamburger Mobile -->
                    <button id="openSidebar" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900">
                        @yield('page-title', 'Dashboard')
                    </h1>
                </div>

                <!-- Kanan Header: Search & Notifikasi -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="hidden md:block relative">
                        <input 
                            type="text" 
                            placeholder="Cari resi / kode SKU..." 
                            class="w-64 rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-4 py-1.5 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none"
                        >
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <!-- Tombol & Dropdown Notifikasi Log Terbaru -->
                    <div class="relative" id="notificationWrapper">
                        <button
                            type="button"
                            id="notificationBtn"
                            aria-label="Notifikasi Aktivitas"
                            class="relative p-2 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition cursor-pointer"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>

                            <!-- Badge Angka Notifikasi Belum Dibaca -->
                            <span
                                id="notifBadge"
                                @class([
                                    'absolute -top-0.5 -right-0.5 min-w-5 h-5 px-1 rounded-full bg-rose-600 text-white text-[11px] font-bold flex items-center justify-center ring-2 ring-white transition-all',
                                    'hidden' => ($unreadNotificationCount ?? 0) === 0,
                                ])
                            >
                                {{ ($unreadNotificationCount ?? 0) > 99 ? '99+' : ($unreadNotificationCount ?? 0) }}
                            </span>
                        </button>

                        <!-- Panel Dropdown Notifikasi -->
                        <div
                            id="notificationDropdown"
                            class="hidden absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white border border-slate-200 shadow-xl z-50 overflow-hidden"
                        >
                            <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-slate-900">Log &amp; Notifikasi</span>
                                    <span id="notifDropdownCount" class="px-2 py-0.5 text-[11px] font-semibold rounded-full bg-indigo-50 text-indigo-600">
                                        {{ $unreadNotificationCount ?? 0 }} baru
                                    </span>
                                </div>
                                <button
                                    type="button"
                                    id="markAllReadBtn"
                                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 cursor-pointer"
                                >
                                    Tandai semua dibaca
                                </button>
                            </div>

                            <div id="notifList" class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                                @forelse ($recentActivityLogs ?? [] as $log)
                                    @php
                                        $dotColor = match($log->type) {
                                            'success' => 'bg-emerald-500',
                                            'warning' => 'bg-amber-500',
                                            default => 'bg-indigo-500',
                                        };
                                        $actorName = $log->actor_name
                                            ?? $log->user?->name
                                            ?? 'Sistem';
                                        $actorRole = $log->user?->role ?? null;
                                    @endphp
                                    <button
                                        type="button"
                                        data-log-id="{{ $log->id }}"
                                        @class([
                                            'notif-item w-full text-left px-4 py-3 hover:bg-slate-50 transition flex items-start gap-3 cursor-pointer',
                                            'bg-indigo-50/40' => !$log->is_read,
                                        ])
                                    >
                                        <span class="mt-1.5 w-2 h-2 rounded-full shrink-0 {{ $dotColor }}"></span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-slate-900 {{ $log->is_read ? 'opacity-75' : '' }}">
                                                {{ $log->title }}
                                            </p>
                                            @if ($log->description)
                                                <p class="text-[11px] text-slate-500 mt-0.5 truncate">{{ $log->description }}</p>
                                            @endif
                                            <div class="flex items-center flex-wrap gap-x-2 gap-y-0.5 text-[10px] text-slate-400 mt-1">
                                                <span class="font-medium text-slate-600">
                                                    Oleh: {{ $actorName }}@if($actorRole && !str_contains($actorName, "($actorRole)")) ({{ $actorRole }})@endif
                                                </span>
                                                <span>&bull;</span>
                                                <span>{{ $log->created_at?->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        @if (!$log->is_read)
                                            <span class="notif-unread-dot w-2 h-2 rounded-full bg-rose-500 shrink-0 mt-1.5"></span>
                                        @endif
                                    </button>
                                @empty
                                    <div class="py-8 px-4 text-center text-xs text-slate-400">
                                        Belum ada log aktivitas tercatat.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area (Scrollable) -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>
    </div>

    <!-- Script Toggle Sidebar Mobile -->
    <!-- Script Notifikasi & Sidebar Mobile -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');

        function toggleSidebar(show) {
            sidebar.classList.toggle('-translate-x-full', !show);
            backdrop.classList.toggle('hidden', !show);
        }

        openBtn?.addEventListener('click', () => toggleSidebar(true));
        closeBtn?.addEventListener('click', () => toggleSidebar(false));
        backdrop?.addEventListener('click', () => toggleSidebar(false));

        // Interaksi Dropdown Notifikasi
        const notifBtn = document.getElementById('notificationBtn');
        const notifDropdown = document.getElementById('notificationDropdown');
        const notifWrapper = document.getElementById('notificationWrapper');
        const notifBadge = document.getElementById('notifBadge');
        const notifDropdownCount = document.getElementById('notifDropdownCount');
        const markAllReadBtn = document.getElementById('markAllReadBtn');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        function syncBadgeCount(count) {
            if (count <= 0) {
                notifBadge?.classList.add('hidden');
                if (notifDropdownCount) notifDropdownCount.textContent = '0 baru';
            } else {
                notifBadge?.classList.remove('hidden');
                if (notifBadge) notifBadge.textContent = count > 99 ? '99+' : String(count);
                if (notifDropdownCount) notifDropdownCount.textContent = `${count} baru`;
            }
        }

        notifBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown?.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!notifWrapper?.contains(e.target)) {
                notifDropdown?.classList.add('hidden');
            }
        });

        // Klik item notifikasi -> tandai sudah dibaca & kurangi angka badge
        document.querySelectorAll('.notif-item').forEach((item) => {
            item.addEventListener('click', async () => {
                const unreadDot = item.querySelector('.notif-unread-dot');
                if (!unreadDot) return;

                const logId = item.dataset.logId;
                unreadDot.remove();
                item.classList.remove('bg-indigo-50/40');
                item.querySelector('p')?.classList.add('opacity-75');

                const currentCount = document.querySelectorAll('.notif-unread-dot').length;
                syncBadgeCount(currentCount);

                await fetch(`/notifications/${logId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });
            });
        });

        // Klik "Tandai semua dibaca" -> hilangkan semua titik & sembunyikan badge angka
        markAllReadBtn?.addEventListener('click', async () => {
            document.querySelectorAll('.notif-unread-dot').forEach((dot) => dot.remove());
            document.querySelectorAll('.notif-item').forEach((item) => {
                item.classList.remove('bg-indigo-50/40');
                item.querySelector('p')?.classList.add('opacity-75');
            });

            syncBadgeCount(0);

            await fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });
        });
    </script>
</body>
</html>