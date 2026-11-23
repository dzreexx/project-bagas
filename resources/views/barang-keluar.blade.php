@extends('layouts.app')

@section('title', 'Barang Keluar')
@section('page-title', 'Barang Keluar (Outbound)')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500">Kelola picking barang, packing pesanan, dan pelepasan stok ke area muat.</p>
        </div>
        <button type="button" class="px-4 py-2 bg-indigo-600 rounded-xl text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm inline-flex items-center gap-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Buat Pengeluaran Barang</span>
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Keluar Hari Ini</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">215 <span class="text-xs font-normal text-slate-500">Koli</span></p>
            <p class="text-xs text-slate-500 mt-1">19 order pengiriman</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Sedang Picking &amp; Packing</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">7 <span class="text-xs font-normal text-slate-500">Order</span></p>
            <p class="text-xs text-slate-500 mt-1">Zona A, B, dan C</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Siap di Loading Dock</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">12 <span class="text-xs font-normal text-slate-500">Order</span></p>
            <p class="text-xs text-slate-500 mt-1">Menunggu jadwal truk</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="font-bold text-slate-900">Daftar Pengeluaran Barang</h2>
            <span class="text-xs text-slate-500">Outbound Queue</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3 px-6 font-semibold">No. Outbound</th>
                        <th class="py-3 px-6 font-semibold">Tujuan / Cabang</th>
                        <th class="py-3 px-6 font-semibold">Barang</th>
                        <th class="py-3 px-6 font-semibold">Jumlah</th>
                        <th class="py-3 px-6 font-semibold">Dock</th>
                        <th class="py-3 px-6 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-900">OUT-20261123-09</td>
                        <td class="py-3.5 px-6 text-slate-700">Cabang Bandung Timur</td>
                        <td class="py-3.5 px-6 text-slate-700">Kemasan Karton Industrial</td>
                        <td class="py-3.5 px-6 font-semibold">85 Palet</td>
                        <td class="py-3.5 px-6 text-slate-500">Dock 02</td>
                        <td class="py-3.5 px-6">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">Proses Muat</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-900">OUT-20261123-10</td>
                        <td class="py-3.5 px-6 text-slate-700">Hub Surabaya</td>
                        <td class="py-3.5 px-6 text-slate-700">Sensor Barcode Handheld</td>
                        <td class="py-3.5 px-6 font-semibold">30 Unit</td>
                        <td class="py-3.5 px-6 text-slate-500">Dock 01</td>
                        <td class="py-3.5 px-6">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">Siap Kirim</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection