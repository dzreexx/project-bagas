@extends('layouts.app')

@section('title', 'Pengiriman & Armada')
@section('page-title', 'Pengiriman & Armada')

@section('content')
    @if (session('success'))
        <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700">
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500">Pantau posisi armada pengiriman, status surat jalan, dan ketersediaan unit truk.</p>
        </div>
        <button type="button" id="openShipmentModalBtn" class="px-4 py-2 bg-indigo-600 rounded-xl text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm inline-flex items-center gap-2 self-start sm:self-auto cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Jadwalkan Pengiriman</span>
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Dalam Perjalanan</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">{{ number_format($summary['dalam_perjalanan']) }} <span class="text-xs font-normal text-slate-500">Armada/Trip</span></p>
            <p class="text-xs text-slate-500 mt-1">Rute Jabodetabek &amp; Antar Kota</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Armada Standby</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($summary['standby']) }} <span class="text-xs font-normal text-slate-500">Unit</span></p>
            <p class="text-xs text-slate-500 mt-1">Siap ditugaskan di pool</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Perawatan / Servis</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($summary['perawatan']) }} <span class="text-xs font-normal text-slate-500">Unit</span></p>
            <p class="text-xs text-slate-500 mt-1">Bengkel rekanan</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden mb-6">
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
                        <th class="py-3 px-6 font-semibold">Outbound</th>
                        <th class="py-3 px-6 font-semibold">Tujuan</th>
                        <th class="py-3 px-6 font-semibold">Estimasi Tiba</th>
                        <th class="py-3 px-6 font-semibold">Status</th>
                        <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($shipments as $row)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-900">{{ $row->shipment_number }}</td>
                            <td class="py-3.5 px-6">
                                <div class="font-medium text-slate-900">{{ $row->fleet?->vehicle_name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">Driver: {{ $row->fleet?->driver_name ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-6 font-mono text-xs text-slate-500">{{ $row->outboundOrder?->outbound_number ?? '-' }}</td>
                            <td class="py-3.5 px-6 text-slate-700">{{ $row->destination }}</td>
                            <td class="py-3.5 px-6 text-slate-600">{{ $row->eta_label }}</td>
                            <td class="py-3.5 px-6">
                                @if ($row->status === 'terkirim')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">Terkirim</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">Dalam Perjalanan</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <form action="{{ route('pengiriman-armada.status', $row) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $row->status === 'terkirim' ? 'dalam_perjalanan' : 'terkirim' }}">
                                    <button type="submit" class="px-3 py-1 rounded-lg bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 cursor-pointer">
                                        {{ $row->status === 'terkirim' ? 'Set Dalam Perjalanan' : 'Tandai Terkirim' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 px-6 text-center text-xs text-slate-400">Belum ada pengiriman terjadwal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daftar Unit Armada -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="font-bold text-slate-900">Daftar Unit Armada</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3 px-6 font-semibold">Unit / Plat</th>
                        <th class="py-3 px-6 font-semibold">Driver</th>
                        <th class="py-3 px-6 font-semibold">Status Unit</th>
                        <th class="py-3 px-6 font-semibold text-right">Ubah Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach ($fleets as $fleet)
                        <tr>
                            <td class="py-3 px-6 font-medium text-slate-900">{{ $fleet->vehicle_name }}</td>
                            <td class="py-3 px-6 text-slate-600">{{ $fleet->driver_name }}</td>
                            <td class="py-3 px-6">
                                @if ($fleet->status === 'standby')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">Standby</span>
                                @elseif ($fleet->status === 'perawatan')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">Perawatan</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">Dalam Perjalanan</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-right">
                                <form action="{{ route('armada.status', $fleet) }}" method="POST" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-xs">
                                        <option value="standby" @selected($fleet->status === 'standby')>Standby</option>
                                        <option value="dalam_perjalanan" @selected($fleet->status === 'dalam_perjalanan')>Dalam Perjalanan</option>
                                        <option value="perawatan" @selected($fleet->status === 'perawatan')>Perawatan</option>
                                    </select>
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-500 cursor-pointer">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Jadwalkan Pengiriman -->
    <div id="shipmentModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <h3 class="font-bold text-slate-900">Jadwalkan Pengiriman &amp; Armada</h3>
                <button type="button" id="closeShipmentModalBtn" class="text-slate-400 hover:text-slate-700 cursor-pointer">&times;</button>
            </div>
            <form action="{{ route('pengiriman-armada.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">No. Pengiriman</label>
                        <input type="text" name="shipment_number" required value="SHP-{{ now()->format('Ymd') }}-{{ rand(10,99) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Referensi Outbound (Opsional)</label>
                        <select name="outbound_order_id" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                            <option value="">-- Tanpa Outbound --</option>
                            @foreach ($outbounds as $ob)
                                <option value="{{ $ob->id }}">{{ $ob->outbound_number }} — {{ $ob->destination_branch }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Armada &amp; Driver</label>
                    <select name="fleet_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                        @foreach ($fleets as $fl)
                            <option value="{{ $fl->id }}">{{ $fl->vehicle_name }} ({{ $fl->driver_name }}) — {{ str_replace('_', ' ', $fl->status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tujuan</label>
                        <input type="text" name="destination" required placeholder="Bandung" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Estimasi Tiba</label>
                        <input type="text" name="eta_label" required placeholder="Besok, 08:00 WIB" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                        <select name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                            <option value="dalam_perjalanan">Dalam Perjalanan</option>
                            <option value="terkirim">Terkirim</option>
                        </select>
                    </div>
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" id="cancelShipmentModalBtn" class="px-4 py-2 rounded-xl border border-slate-200 text-sm text-slate-600 cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-500 cursor-pointer">Simpan Pengiriman</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const shpModal = document.getElementById('shipmentModal');
        document.getElementById('openShipmentModalBtn')?.addEventListener('click', () => { shpModal.classList.remove('hidden'); shpModal.classList.add('flex'); });
        ['closeShipmentModalBtn', 'cancelShipmentModalBtn'].forEach(id => document.getElementById(id)?.addEventListener('click', () => { shpModal.classList.add('hidden'); shpModal.classList.remove('flex'); }));
    </script>
@endsection