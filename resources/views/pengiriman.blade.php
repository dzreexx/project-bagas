@extends('layouts.app')

@section('title', 'Pengiriman & Armada')
@section('page-title', 'Pengiriman & Armada')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500">Pantau posisi armada pengiriman, status surat jalan, dan ketersediaan unit truk.</p>
        </div>
        <button type="button" class="px-4 py-2 bg-indigo-600 rounded-xl text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm inline-flex items-center gap-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Jadwalkan Pengiriman</span>
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Dalam Perjalanan</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">14 <span class="text-xs font-normal text-slate-500">Armada</span></p>
            <p class="text-xs text-slate-500 mt-1">Rute Jabodetabek &amp; Jawa Barat</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Armada Standby</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">6 <span class="text-xs font-normal text-slate-500">Unit</span></p>
            <p class="text-xs text-slate-500 mt-1">Siap ditugaskan di pool</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Perawatan / Servis</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">2 <span class="text-xs font-normal text-slate-500">Unit</span></p>
            <p class="text-xs text-slate-500 mt-1">Bengkel rekanan</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="font-bold text-slate-900">Status Pengiriman &amp; Armada Aktif</h2>
            <span class="text-xs text-slate-500">Update Real-time</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3 px-6 font-semibold">No. Pengiriman</th>
                        <th class="py-3 px-6 font-semibold">Armada &amp; Driver</th>
                        <th class="py-3 px-6 font-semibold">Tujuan</th>
                        <th class="py-3 px-6 font-semibold">Estimasi Tiba</th>
                        <th class="py-3 px-6 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-900">SHP-20261123-04</td>
                        <td class="py-3.5 px-6">
                            <div class="font-medium text-slate-900">Wingbox B 9241 KYT</div>
                            <div class="text-xs text-slate-400">Driver: Hendra Wijaya</div>
                        </td>
                        <td class="py-3.5 px-6 text-slate-700">Bandung</td>
                        <td class="py-3.5 px-6 text-slate-600">Hari ini, 23:30 WIB</td>
                        <td class="py-3.5 px-6">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">Dalam Perjalanan</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-900">SHP-20261123-05</td>
                        <td class="py-3.5 px-6">
                            <div class="font-medium text-slate-900">Box Engkel B 8810 URA</div>
                            <div class="text-xs text-slate-400">Driver: Rudi Santoso</div>
                        </td>
                        <td class="py-3.5 px-6 text-slate-700">Karawang</td>
                        <td class="py-3.5 px-6 text-slate-600">Tiba 18:15 WIB</td>
                        <td class="py-3.5 px-6">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">Terkirim</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection