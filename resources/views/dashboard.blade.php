@extends('layouts.app')

@section('title', 'Dashboard Gudang')
@section('page-title', 'Ringkasan Operasional Gudang')

@section('content')
    <!-- Banner Info Singkat -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500">Pantau pergerakan stok, kapasitas rak, dan status pengiriman hari ini.</p>
        </div>
        <div class="flex items-center gap-2">
            <button class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 transition shadow-2xs">
                Export Laporan
            </button>
            <button class="px-4 py-2 bg-indigo-600 rounded-xl text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm">
                + Catat Barang Masuk
            </button>
        </div>
    </div>

    <!-- Grid 4 Kartu Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-slate-500">Total SKU Barang</span>
                <span class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </span>
            </div>
            <div class="text-2xl font-bold text-slate-900">1,428</div>
            <p class="text-xs text-emerald-600 font-medium mt-1">+24 SKU baru bulan ini</p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-slate-500">Barang Masuk (Hari Ini)</span>
                <span class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </span>
            </div>
            <div class="text-2xl font-bold text-slate-900">340 <span class="text-sm font-normal text-slate-400">Koli</span></div>
            <p class="text-xs text-slate-500 mt-1">Dari 8 supplier terjadwal</p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-slate-500">Barang Keluar (Hari Ini)</span>
                <span class="p-2 rounded-lg bg-amber-50 text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                </span>
            </div>
            <div class="text-2xl font-bold text-slate-900">215 <span class="text-sm font-normal text-slate-400">Koli</span></div>
            <p class="text-xs text-slate-500 mt-1">14 armada dalam pengiriman</p>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-slate-500">Kapasitas Gudang</span>
                <span class="p-2 rounded-lg bg-rose-50 text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                    </svg>
                </span>
            </div>
            <div class="text-2xl font-bold text-slate-900">78.5%</div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
                <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 78.5%"></div>
            </div>
        </div>
    </div>

    <!-- Tabel Aktivitas Logistik Terbaru -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="font-bold text-slate-900">Aktivitas Keluar/Masuk Terbaru</h2>
            <a href="#" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500">Lihat Semua &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3 px-6 font-semibold">No. Dokumen</th>
                        <th class="py-3 px-6 font-semibold">Nama Barang</th>
                        <th class="py-3 px-6 font-semibold">Tipe</th>
                        <th class="py-3 px-6 font-semibold">Jumlah</th>
                        <th class="py-3 px-6 font-semibold">Lokasi Rak</th>
                        <th class="py-3 px-6 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-medium text-slate-900">IN-20261123-01</td>
                        <td class="py-3.5 px-6 text-slate-700">Komponen Elektronik A2</td>
                        <td class="py-3.5 px-6">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-50 text-emerald-700">Masuk</span>
                        </td>
                        <td class="py-3.5 px-6 font-medium">120 Box</td>
                        <td class="py-3.5 px-6 text-slate-500">Zona A - Rak 04</td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                            </span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-medium text-slate-900">OUT-20261123-09</td>
                        <td class="py-3.5 px-6 text-slate-700">Kemasan Karton Industrial</td>
                        <td class="py-3.5 px-6">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-50 text-amber-700">Keluar</span>
                        </td>
                        <td class="py-3.5 px-6 font-medium">85 Palet</td>
                        <td class="py-3.5 px-6 text-slate-500">Zona C - Loading Dock 2</td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Proses Muat
                            </span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-medium text-slate-900">IN-20261123-02</td>
                        <td class="py-3.5 px-6 text-slate-700">Sparepart Mesin Berat</td>
                        <td class="py-3.5 px-6">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-50 text-emerald-700">Masuk</span>
                        </td>
                        <td class="py-3.5 px-6 font-medium">40 Unit</td>
                        <td class="py-3.5 px-6 text-slate-500">Zona B - Rak 11</td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Menunggu QC
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection