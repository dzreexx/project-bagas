@extends('layouts.app')

@section('title', 'User Administration')
@section('page-title', 'User Administration')

@section('content')
    <!-- Notifikasi Sukses dari Session Laravel -->
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
            <p class="font-semibold mb-1">Gagal menyimpan data user:</p>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header Aksi Halaman -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500">Kelola akun pengguna sistem gudang, hak akses role, dan status aktif akun.</p>
        </div>
        <button type="button" id="openAddUserModal" class="px-4 py-2 bg-indigo-600 rounded-xl text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm inline-flex items-center gap-2 self-start sm:self-auto cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            <span>Tambah User Baru</span>
        </button>
    </div>

    <!-- Ringkasan User & Role -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Akun Terdaftar</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['total'] }} <span class="text-xs font-normal text-slate-500">User</span></p>
            <p class="text-xs text-emerald-600 font-medium mt-1">{{ $stats['active'] }} akun aktif</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Admin &amp; Supervisor</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $stats['admin'] }} <span class="text-xs font-normal text-slate-500">Akun</span></p>
            <p class="text-xs text-slate-500 mt-1">Akses penuh modul &amp; laporan</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Akun Nonaktif / Suspend</p>
            <p class="text-2xl font-bold text-rose-600 mt-1">{{ $stats['inactive'] }} <span class="text-xs font-normal text-slate-500">Akun</span></p>
            <p class="text-xs text-slate-500 mt-1">Diblokir sementara oleh IT</p>
        </div>
    </div>

    <!-- Filter & Pencarian User -->
    <form action="{{ route('user-administration') }}" method="GET" class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div class="md:col-span-2 relative">
                <input
                    name="search"
                    type="text"
                    value="{{ request('search') }}"
                    placeholder="Cari nama user, email, atau ID karyawan..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-4 py-2 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:outline-none"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <div>
                <select name="role" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    <option value="">Semua Role</option>
                    @foreach (['IT Administrator', 'Kepala Gudang', 'Staff Inbound/Outbound', 'Dispatcher Armada'] as $roleOption)
                        <option value="{{ $roleOption }}" @selected(request('role') === $roleOption)>{{ $roleOption }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="Aktif" @selected(request('status') === 'Aktif')>Aktif</option>
                    <option value="Nonaktif" @selected(request('status') === 'Nonaktif')>Nonaktif</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition cursor-pointer">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('user-administration') }}" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 flex items-center">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Tabel Daftar Pengguna -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="font-bold text-slate-900">Daftar Pengguna Sistem</h2>
            <span class="text-xs text-slate-500">Menampilkan {{ $users->count() }} user</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3 px-6 font-semibold">Pengguna</th>
                        <th class="py-3 px-6 font-semibold">ID Karyawan</th>
                        <th class="py-3 px-6 font-semibold">Role / Hak Akses</th>
                        <th class="py-3 px-6 font-semibold">Login Terakhir</th>
                        <th class="py-3 px-6 font-semibold">Status</th>
                        <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($users as $user)
                        @php
                            $isAktif = $user->status === 'Aktif';
                            $initials = collect(explode(' ', trim($user->name)))
                                ->take(2)
                                ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                                ->implode('');
                            $badgeClass = match($user->role) {
                                'IT Administrator' => 'bg-purple-50 text-purple-700',
                                'Kepala Gudang' => 'bg-indigo-50 text-indigo-700',
                                default => 'bg-slate-100 text-slate-700',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full {{ $isAktif ? 'bg-indigo-50 border border-indigo-200 text-indigo-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center font-bold text-xs">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-6 font-mono text-xs text-slate-600">{{ $user->employee_id ?? '-' }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">{{ $user->role }}</span>
                            </td>
                            <td class="py-3.5 px-6 text-slate-500 text-xs">
                                {{ $user->last_login_at ? $user->last_login_at->format('d M Y, H:i') : 'Belum pernah login' }}
                            </td>
                            <td class="py-3.5 px-6">
                                @if ($isAktif)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <form action="{{ route('user-administration.toggle-status', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 cursor-pointer">
                                        {{ $isAktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-sm text-slate-400">
                                Belum ada data pengguna di database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TAMBAH USER BARU -->
    <div id="addUserModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity">
        <div id="addUserModalContent" class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden transform transition-all duration-200 scale-95 opacity-0">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600/10 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Tambah User Baru</h3>
                        <p class="text-xs text-slate-500">Data akan disimpan langsung ke tabel users</p>
                    </div>
                </div>
                <button type="button" id="closeAddUserModal" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('user-administration.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <div>
                    <label for="userName" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input
                        id="userName"
                        name="name"
                        type="text"
                        required
                        value="{{ old('name') }}"
                        placeholder="Contoh: Ahmad Fauzi"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="userEmpId" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            ID Karyawan <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="userEmpId"
                            name="employee_id"
                            type="text"
                            required
                            value="{{ old('employee_id') }}"
                            placeholder="EMP-025"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm font-mono text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        >
                    </div>

                    <div>
                        <label for="userRole" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Role / Hak Akses <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="userRole"
                            name="role"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        >
                            @foreach (['Staff Inbound/Outbound', 'Dispatcher Armada', 'Kepala Gudang', 'IT Administrator'] as $roleOpt)
                                <option value="{{ $roleOpt }}" @selected(old('role', 'Staff Inbound/Outbound') === $roleOpt)>{{ $roleOpt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="userEmail" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Email Perusahaan <span class="text-rose-500">*</span>
                    </label>
                    <input
                        id="userEmail"
                        name="email"
                        type="email"
                        required
                        value="{{ old('email') }}"
                        placeholder="nama.karyawan@logisticshub.local"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="userPassword" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Password Sementara <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                id="userPassword"
                                name="password"
                                type="password"
                                required
                                minlength="6"
                                value="Gudang2026!"
                                class="w-full rounded-xl border border-slate-300 bg-white pl-3.5 pr-9 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            >
                            <button type="button" id="toggleModalPassword" title="Lihat/Sembunyikan Password" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="userStatus" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Status Akun
                        </label>
                        <select
                            id="userStatus"
                            name="status"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                        >
                            <option value="Aktif" @selected(old('status', 'Aktif') === 'Aktif')>Aktif (Langsung bisa login)</option>
                            <option value="Nonaktif" @selected(old('status') === 'Nonaktif')>Nonaktif (Pending aktivasi)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" id="cancelAddUserModal" class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm cursor-pointer">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('addUserModal');
            const modalContent = document.getElementById('addUserModalContent');
            const openModalBtn = document.getElementById('openAddUserModal');
            const closeModalBtn = document.getElementById('closeAddUserModal');
            const cancelModalBtn = document.getElementById('cancelAddUserModal');
            const togglePasswordBtn = document.getElementById('toggleModalPassword');
            const passwordInput = document.getElementById('userPassword');
            const empIdInput = document.getElementById('userEmpId');

            function openModal() {
                if (!empIdInput.value) {
                    const nextNum = String({{ $stats['total'] }} + 1).padStart(3, '0');
                    empIdInput.value = `EMP-${nextNum}`;
                }
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                requestAnimationFrame(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                });
                document.getElementById('userName').focus();
            }

            function closeModal() {
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 180);
            }

            openModalBtn.addEventListener('click', openModal);
            closeModalBtn.addEventListener('click', closeModal);
            cancelModalBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });

            togglePasswordBtn.addEventListener('click', () => {
                passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
            });

            // Jika validasi gagal, buka kembali modal otomatis agar user bisa memperbaiki input
            @if ($errors->any())
                openModal();
            @endif
        });
    </script>
@endsection