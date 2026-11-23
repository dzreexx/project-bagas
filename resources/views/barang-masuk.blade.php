@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk (Inbound)')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500">Catat penerimaan barang dari supplier, verifikasi surat jalan, dan pantau proses QC.</p>
        </div>
        <button type="button" class="px-4 py-2 bg-indigo-600 rounded-xl text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm inline-flex items-center gap-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Input Barang Masuk</span>
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Masuk Hari Ini</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">340 <span class="text-xs font-normal text-slate-500">Koli</span></p>
            <p class="text-xs text-emerald-600 font-medium mt-1">8 Surat Jalan diproses</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Menunggu QC</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">12 <span class="text-xs font-normal text-slate-500">Dokumen</span></p>
            <p class="text-xs text-slate-500 mt-1">Zona Receiving &amp; Inspeksi</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Selesai Put-Away</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">295 <span class="text-xs font-normal text-slate-500">Koli</span></p>
            <p class="text-xs text-slate-500 mt-1">Sudah masuk ke rak tujuan</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="font-bold text-slate-900">Daftar Penerimaan Barang</h2>
            <span class="text-xs text-slate-500">Hari ini, 23 Nov 2026</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3 px-6 font-semibold">No. Inbound</th>
                        <th class="py-3 px-6 font-semibold">Supplier</th>
                        <th class="py-3 px-6 font-semibold">Barang / SKU</th>
                        <th class="py-3 px-6 font-semibold">Jumlah</th>
                        <th class="py-3 px-6 font-semibold">Tujuan Rak</th>
                        <th class="py-3 px-6 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-900">IN-20261123-01</td>
                        <td class="py-3.5 px-6 text-slate-700">PT Elektro Jaya Abadi</td>
                        <td class="py-3.5 px-6 text-slate-700">Komponen Elektronik A2</td>
                        <td class="py-3.5 px-6 font-semibold">120 Box</td>
                        <td class="py-3.5 px-6 text-slate-500">Zona A - Rak 04</td>
                        <td class="py-3.5 px-6">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">Selesai</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-900">IN-20261123-02</td>
                        <td class="py-3.5 px-6 text-slate-700">CV Teknik Mandiri</td>
                        <td class="py-3.5 px-6 text-slate-700">Sparepart Mesin Berat</td>
                        <td class="py-3.5 px-6 font-semibold">40 Unit</td>
                        <td class="py-3.5 px-6 text-slate-500">Zona B - Rak 11</td>
                        <td class="py-3.5 px-6">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">Menunggu QC</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection