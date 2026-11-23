<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Sistem Logistik Gudang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen relative flex items-center justify-center p-4 antialiased bg-slate-900">

    <!-- Background Foto Logistik Gudang -->
    <div class="absolute inset-0 z-0">
        <img 
            src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&q=80&w=1920" 
            alt="Logistics Warehouse Background" 
            class="w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-slate-950/65 backdrop-blur-[3px]"></div>
    </div>

    <div class="w-full max-w-md z-10 relative">
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/30 mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-white">LUPA KATA SANDI</h1>
            <p class="text-sm text-slate-300 mt-1">Pemulihan akses akun LOGISTICS HUB</p>
        </div>

        <!-- Card -->
        <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-white/20 p-6 sm:p-8">
            <div class="mb-5">
                <h2 class="text-lg font-bold text-slate-900">Atur ulang kata sandi</h2>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Masukkan alamat email akun Anda. Jika akun terkunci akibat 10x gagal login, tim IT juga dapat membantu mengaktifkannya kembali.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 px-3.5 py-3 text-xs font-medium text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 px-3.5 py-3 text-xs font-medium text-rose-700">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Alamat Email Terdaftar
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        value="{{ old('email') }}"
                        placeholder="nama@logisticshub.local"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-500 cursor-pointer"
                >
                    Kirim Permintaan Reset
                </button>
            </form>

            <div class="mt-5 pt-4 border-t border-slate-100 text-center">
                <a href="{{ route('login') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition">
                    &larr; Kembali ke halaman masuk
                </a>
            </div>
        </div>
    </div>
</body>
</html>