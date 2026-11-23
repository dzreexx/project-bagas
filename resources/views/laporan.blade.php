@extends('layouts.app')

@section('title', 'Laporan Gudang')
@section('page-title', 'Laporan Gudang')

@section('content')
    <div class="mb-6">
        <p class="text-sm text-slate-500">Unduh atau tinjau rekapitulasi mutasi stok, arus inbound/outbound, dan utilitas rak.</p>
    </div>

    <!-- Filter Periode Laporan -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-5 mb-6">
        <form action="#" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jenis Laporan</label>
                <select class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    <option>Mutasi Stok Bulanan</option>
                    <option>Rekap Barang Masuk &amp; Keluar</option>
                    <option>Audit Stok Menipis / Habis</option>
                    <option>Performa Pengiriman Armada</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Mulai</label>
                <input type="date" value="2026-11-01" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Akhir</label>
                <input type="date" value="2026-11-23" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none">
            </div>
            <div>
                <button type="button" class="w-full px-4 py-2 bg-indigo-600 rounded-xl text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm">
                    Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Laporan Siap Unduh -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="font-bold text-slate-900">Arsip Laporan Tersedia</h2>
        </div>
        <div class="divide-y divide-slate-100">
            <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-slate-50/70 transition">
                <div>
                    <p class="font-semibold text-slate-900 text-sm">Laporan Mutasi Stok — November 2026</p>
                    <p class="text-xs text-slate-500 mt-0.5">Dibuat otomatis • Format PDF &amp; Excel (.xlsx)</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100">PDF</button>
                    <button type="button" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold">Excel</button>
                </div>
            </div>
            <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-slate-50/70 transition">
                <div>
                    <p class="font-semibold text-slate-900 text-sm">Rekap Pengiriman Armada Mingguan (Minggu ke-3)</p>
                    <p class="text-xs text-slate-500 mt-0.5">Periode 15 Nov – 21 Nov 2026</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100">PDF</button>
                    <button type="button" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-semibold">Excel</button>
                </div>
            </div>
        </div>
    </div>
@endsection