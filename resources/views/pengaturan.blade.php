@extends('layouts.app')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan Sistem & Gudang')

@section('content')
    @php
        $initials = collect(explode(' ', trim($user->name)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->implode('') ?: 'U';

        $activeTab = session('active_tab', 'profile');
        if ($errors->hasAny(['current_password', 'password'])) {
            $activeTab = 'security';
        } elseif ($errors->hasAny(['theme_mode', 'font_size', 'accent_color'])) {
            $activeTab = 'appearance';
        }
    @endphp

    <!-- Notifikasi Sukses -->
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm font-medium text-emerald-800">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Notifikasi Error Validasi -->
    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700">
            <p class="font-semibold mb-1">Periksa kembali input Anda:</p>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6">
        <p class="text-sm text-slate-500">Atur profil akun, keamanan kata sandi, konfigurasi gudang, serta preferensi tampilan sistem.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Kartu Profil & Navigasi Tab Pengaturan -->
        <div class="space-y-5 self-start">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-200 flex items-center justify-center text-indigo-600 font-bold text-lg shrink-0">
                        {{ $initials }}
                    </div>
                    <div class="min-w-0">
                        <h2 class="font-bold text-slate-900 truncate">{{ $user->name }}</h2>
                        <p class="text-xs text-slate-500 truncate">{{ $user->role }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 text-[11px] font-medium rounded-full {{ $user->status === 'Aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                            {{ $user->status }}
                        </span>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 text-xs text-slate-500 space-y-2">
                    <div class="flex justify-between">
                        <span>ID Karyawan</span>
                        <span class="font-mono font-semibold text-slate-700">{{ $user->employee_id ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Kode Gudang</span>
                        <span class="font-mono font-semibold text-slate-700">{{ $user->warehouse_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Cabang</span>
                        <span class="font-semibold text-slate-700 truncate max-w-[160px]">{{ $user->warehouse_name }}</span>
                    </div>
                </div>
            </div>

            <!-- Menu Tab Pengaturan -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-2 space-y-1">
                <button type="button" data-tab="profile" class="settings-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profil &amp; Gudang</span>
                </button>

                <button type="button" data-tab="security" class="settings-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span>Keamanan &amp; Password</span>
                </button>

                <button type="button" data-tab="appearance" class="settings-tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    <span>Tema, Font &amp; Sistem</span>
                </button>
            </div>
        </div>

        <!-- Kolom Kanan: Panel Konten Tab -->
        <div class="lg:col-span-2 space-y-6">

            <!-- TAB 1: PROFIL & KONFIGURASI GUDANG -->
            <div id="tab-profile" class="settings-panel bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-6">
                <h2 class="font-bold text-slate-900 mb-1">Profil Akun &amp; Konfigurasi Gudang</h2>
                <p class="text-xs text-slate-500 mb-5">Perbarui identitas akun serta ambang batas peringatan stok cabang.</p>

                <form action="{{ route('pengaturan.profile') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="profileName" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
                            <input id="profileName" name="name" type="text" required value="{{ old('name', $user->name) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="profileEmail" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                            <input id="profileEmail" name="email" type="email" required value="{{ old('email', $user->email) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label for="warehouseName" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Cabang Gudang</label>
                            <input id="warehouseName" name="warehouse_name" type="text" required value="{{ old('warehouse_name', $user->warehouse_name) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="warehouseCode" class="block text-sm font-medium text-slate-700 mb-1.5">Kode Gudang</label>
                            <input id="warehouseCode" name="warehouse_code" type="text" required value="{{ old('warehouse_code', $user->warehouse_code) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm font-mono text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="lowStockThreshold" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Batas Minimum Peringatan Stok Menipis (%)
                        </label>
                        <input id="lowStockThreshold" name="low_stock_threshold" type="number" required value="{{ old('low_stock_threshold', $user->low_stock_threshold) }}" min="1" max="100" class="w-full sm:w-48 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div class="pt-2 flex justify-end gap-2">
                        <button type="reset" class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 cursor-pointer">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm cursor-pointer">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: KEAMANAN & GANTI PASSWORD -->
            <div id="tab-security" class="settings-panel hidden bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-6">
                <h2 class="font-bold text-slate-900 mb-1">Ganti Kata Sandi</h2>
                <p class="text-xs text-slate-500 mb-5">Pastikan kata sandi baru minimal 6 karakter dan berbeda dari kata sandi lama.</p>

                <form action="{{ route('pengaturan.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="currentPassword" class="block text-sm font-medium text-slate-700 mb-1.5">Kata Sandi Saat Ini</label>
                        <input id="currentPassword" name="current_password" type="password" required autocomplete="current-password" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="newPassword" class="block text-sm font-medium text-slate-700 mb-1.5">Kata Sandi Baru</label>
                            <input id="newPassword" name="password" type="password" required minlength="6" autocomplete="new-password" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="newPasswordConfirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                            <input id="newPasswordConfirmation" name="password_confirmation" type="password" required minlength="6" autocomplete="new-password" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm text-slate-900 focus:bg-white focus:border-indigo-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm cursor-pointer">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 3: CUSTOMISASI TEMA, UKURAN FONT & SISTEM -->
            <div id="tab-appearance" class="settings-panel hidden bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-6">
                <h2 class="font-bold text-slate-900 mb-1">Customisasi Tema, Ukuran Font &amp; Sistem</h2>
                <p class="text-xs text-slate-500 mb-5">Sesuaikan kenyamanan visual antarmuka sistem logistik sesuai preferensi Anda.</p>

                <form action="{{ route('pengaturan.appearance') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Tema Gelap / Terang -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tema Antarmuka</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach (['light' => 'Light Mode (Terang)', 'dark' => 'Dark Mode (Gelap)'] as $modeVal => $modeLabel)
                                <label class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-indigo-400 cursor-pointer transition">
                                    <input
                                        type="radio"
                                        name="theme_mode"
                                        value="{{ $modeVal }}"
                                        @checked(old('theme_mode', $user->theme_mode) === $modeVal)
                                        class="text-indigo-600 focus:ring-indigo-500"
                                    >
                                    <span class="text-sm font-medium text-slate-800">{{ $modeLabel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ukuran Font -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Ukuran Font Sistem</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach (['sm' => 'Kecil (Compact)', 'base' => 'Normal (Default)', 'lg' => 'Besar (Nyaman)'] as $sizeVal => $sizeLabel)
                                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 hover:border-indigo-400 cursor-pointer transition">
                                    <input
                                        type="radio"
                                        name="font_size"
                                        value="{{ $sizeVal }}"
                                        @checked(old('font_size', $user->font_size) === $sizeVal)
                                        class="text-indigo-600 focus:ring-indigo-500"
                                    >
                                    <span class="text-xs sm:text-sm font-medium text-slate-800">{{ $sizeLabel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Warna Aksen -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Warna Aksen Sistem</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach (['indigo' => 'Indigo', 'emerald' => 'Emerald', 'sky' => 'Sky Blue'] as $accVal => $accLabel)
                                <label class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 hover:border-indigo-400 cursor-pointer transition">
                                    <input
                                        type="radio"
                                        name="accent_color"
                                        value="{{ $accVal }}"
                                        @checked(old('accent_color', $user->accent_color) === $accVal)
                                        class="text-indigo-600 focus:ring-indigo-500"
                                    >
                                    <span class="text-sm font-medium text-slate-800">{{ $accLabel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Mode Kepadatan (Compact Spacing) -->
                    <div class="pt-1">
                        <label class="inline-flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                name="compact_mode"
                                value="1"
                                @checked(old('compact_mode', $user->compact_mode))
                                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <div>
                                <span class="text-sm font-medium text-slate-800 block">Aktifkan Mode Ringkas (Compact Spacing)</span>
                                <span class="text-xs text-slate-500">Menampilkan lebih banyak baris data pada tabel &amp; daftar log.</span>
                            </div>
                        </label>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm cursor-pointer">
                            Simpan Preferensi Sistem
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabBtns = document.querySelectorAll('.settings-tab-btn');
            const panels = document.querySelectorAll('.settings-panel');
            let currentTab = '{{ $activeTab }}';

            function activateTab(tabName) {
                currentTab = tabName;
                tabBtns.forEach((btn) => {
                    const isActive = btn.dataset.tab === tabName;
                    btn.classList.toggle('bg-indigo-600', isActive);
                    btn.classList.toggle('text-white', isActive);
                    btn.classList.toggle('shadow-sm', isActive);
                    btn.classList.toggle('text-slate-600', !isActive);
                    btn.classList.toggle('hover:bg-slate-100', !isActive);
                });

                panels.forEach((panel) => {
                    panel.classList.toggle('hidden', panel.id !== `tab-${tabName}`);
                });
            }

            tabBtns.forEach((btn) => {
                btn.addEventListener('click', () => activateTab(btn.dataset.tab));
            });

            // Preview langsung tema & ukuran font sebelum submit
            document.querySelectorAll('input[name="theme_mode"]').forEach((radio) => {
                radio.addEventListener('change', () => {
                    document.documentElement.classList.toggle('dark', radio.value === 'dark');
                    document.body.classList.toggle('bg-slate-950', radio.value === 'dark');
                    document.body.classList.toggle('text-slate-100', radio.value === 'dark');
                    document.body.classList.toggle('bg-slate-100', radio.value !== 'dark');
                    document.body.classList.toggle('text-slate-800', radio.value !== 'dark');
                });
            });

            document.querySelectorAll('input[name="font_size"]').forEach((radio) => {
                radio.addEventListener('change', () => {
                    document.body.classList.remove('text-sm', 'text-[15px]', 'text-base');
                    if (radio.value === 'sm') document.body.classList.add('text-sm');
                    else if (radio.value === 'lg') document.body.classList.add('text-base');
                    else document.body.classList.add('text-[15px]');
                });
            });

            activateTab(currentTab);
        });
    </script>
@endsection