@extends('layouts.app')

@section('title', 'Stok & Inventaris')
@section('page-title', 'Stok & Inventaris Gudang')

@section('content')
    <!-- Header Aksi Halaman -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500">Kelola daftar SKU, pantau sisa stok per rak, dan deteksi barang yang perlu restock.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 transition shadow-2xs inline-flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Export CSV</span>
            </button>
            <button type="button" class="px-4 py-2 bg-indigo-600 rounded-xl text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Barang SKU</span>
            </button>
        </div>
    </div>

    <!-- Ringkasan Singkat Inventaris -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Stok Aman</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">1,284 <span class="text-xs font-normal text-slate-500">SKU</span></p>
            </div>
            <span class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Stok Menipis (Low)</p>
                <p class="text-2xl font-bold text-amber-600 mt-1">118 <span class="text-xs font-normal text-slate-500">SKU</span></p>
            </div>
            <span class="p-3 rounded-xl bg-amber-50 text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Stok Habis (Out)</p>
                <p class="text-2xl font-bold text-rose-600 mt-1">26 <span class="text-xs font-normal text-slate-500">SKU</span></p>
            </div>
            <span class="p-3 rounded-xl bg-rose-50 text-rose-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </span>
        </div>
    </div>

    <!-- Panel Filter & Pencarian -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2 relative">
                <input
                    type="text"
                    placeholder="Cari nama barang, kode SKU, atau lokasi rak..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-4 py-2 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:outline-none"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <div>
                <select class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    <option value="">Semua Kategori</option>
                    <option value="elektronik">Komponen Elektronik</option>
                    <option value="kemasan">Kemasan & Karton</option>
                    <option value="sparepart">Sparepart Mesin</option>
                    <option value="bahan-baku">Bahan Baku</option>
                </select>
            </div>

            <div>
                <select class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    <option value="">Semua Status Stok</option>
                    <option value="aman">Stok Aman</option>
                    <option value="menipis">Stok Menipis</option>
                    <option value="habis">Stok Habis</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Stok & Inventaris -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="font-bold text-slate-900">Daftar Inventaris Barang</h2>
            <span class="text-xs text-slate-500">Menampilkan 5 dari 1,428 SKU</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3 px-6 font-semibold">Kode SKU</th>
                        <th class="py-3 px-6 font-semibold">Nama Barang</th>
                        <th class="py-3 px-6 font-semibold">Kategori</th>
                        <th class="py-3 px-6 font-semibold">Lokasi Rak</th>
                        <th class="py-3 px-6 font-semibold">Stok Saat Ini</th>
                        <th class="py-3 px-6 font-semibold">Status</th>
                        <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <!-- Item 1 -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-700">SKU-ELK-0012</td>
                        <td class="py-3.5 px-6">
                            <div class="font-medium text-slate-900">Komponen Elektronik A2</div>
                            <div class="text-xs text-slate-400">Batch #B-2026-11</div>
                        </td>
                        <td class="py-3.5 px-6 text-slate-600">Komponen Elektronik</td>
                        <td class="py-3.5 px-6 text-slate-600">Zona A - Rak 04</td>
                        <td class="py-3.5 px-6 font-semibold text-slate-900">480 Box</td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aman
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            <button type="button" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 mr-3">Detail</button>
                            <button type="button" class="text-xs font-semibold text-slate-500 hover:text-slate-700">Edit</button>
                        </td>
                    </tr>

                    <!-- Item 2: Stok Menipis -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-700">SKU-KMS-0089</td>
                        <td class="py-3.5 px-6">
                            <div class="font-medium text-slate-900">Kemasan Karton Industrial</div>
                            <div class="text-xs text-slate-400">Min. stok: 100 Palet</div>
                        </td>
                        <td class="py-3.5 px-6 text-slate-600">Kemasan & Karton</td>
                        <td class="py-3.5 px-6 text-slate-600">Zona C - Rak 02</td>
                        <td class="py-3.5 px-6 font-semibold text-amber-600">35 Palet</td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menipis
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            <button type="button" class="text-xs font-semibold text-amber-600 hover:text-amber-500 mr-3">Restock</button>
                            <button type="button" class="text-xs font-semibold text-slate-500 hover:text-slate-700">Edit</button>
                        </td>
                    </tr>

                    <!-- Item 3 -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-700">SKU-SPR-0045</td>
                        <td class="py-3.5 px-6">
                            <div class="font-medium text-slate-900">Sparepart Mesin Berat</div>
                            <div class="text-xs text-slate-400">Batch #B-2026-10</div>
                        </td>
                        <td class="py-3.5 px-6 text-slate-600">Sparepart Mesin</td>
                        <td class="py-3.5 px-6 text-slate-600">Zona B - Rak 11</td>
                        <td class="py-3.5 px-6 font-semibold text-slate-900">128 Unit</td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aman
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            <button type="button" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 mr-3">Detail</button>
                            <button type="button" class="text-xs font-semibold text-slate-500 hover:text-slate-700">Edit</button>
                        </td>
                    </tr>

                    <!-- Item 4: Stok Habis -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-700">SKU-BBK-0104</td>
                        <td class="py-3.5 px-6">
                            <div class="font-medium text-slate-900">Stretch Film Wrapping 50cm</div>
                            <div class="text-xs text-slate-400">Min. stok: 50 Roll</div>
                        </td>
                        <td class="py-3.5 px-6 text-slate-600">Bahan Baku</td>
                        <td class="py-3.5 px-6 text-slate-600">Zona C - Rak 09</td>
                        <td class="py-3.5 px-6 font-semibold text-rose-600">0 Roll</td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Habis
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            <button type="button" class="text-xs font-semibold text-rose-600 hover:text-rose-500 mr-3">Restock</button>
                            <button type="button" class="text-xs font-semibold text-slate-500 hover:text-slate-700">Edit</button>
                        </td>
                    </tr>

                    <!-- Item 5 -->
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-700">SKU-ELK-0031</td>
                        <td class="py-3.5 px-6">
                            <div class="font-medium text-slate-900">Sensor Barcode Handheld</div>
                            <div class="text-xs text-slate-400">Batch #B-2026-11</div>
                        </td>
                        <td class="py-3.5 px-6 text-slate-600">Komponen Elektronik</td>
                        <td class="py-3.5 px-6 text-slate-600">Zona A - Rak 01</td>
                        <td class="py-3.5 px-6 font-semibold text-slate-900">94 Unit</td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aman
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            <button type="button" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 mr-3">Detail</button>
                            <button type="button" class="text-xs font-semibold text-slate-500 hover:text-slate-700">Edit</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer Pagination -->
        <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-slate-500">
            <span>Halaman 1 dari 286</span>
            <div class="flex items-center gap-1.5">
                <button type="button" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-400 cursor-not-allowed">Sebelumnya</button>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white font-semibold">1</button>
                <button type="button" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-100">2</button>
                <button type="button" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-100">3</button>
                <button type="button" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-100">Berikutnya</button>
            </div>
        </div>
    </div>
@endsection