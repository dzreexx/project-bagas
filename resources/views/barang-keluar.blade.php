@extends('layouts.app')

@section('title', 'Barang Keluar')
@section('page-title', 'Barang Keluar (Outbound)')

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
            <p class="text-sm text-slate-500">Kelola picking barang, packing pesanan, dan pelepasan stok ke area muat.</p>
        </div>
        <button type="button" id="openOutboundModalBtn" class="px-4 py-2 bg-indigo-600 rounded-xl text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm inline-flex items-center gap-2 self-start sm:self-auto cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Buat Pengeluaran Barang</span>
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Keluar Hari Ini</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($summary['keluar_hari_ini']) }} <span class="text-xs font-normal text-slate-500">Qty</span></p>
            <p class="text-xs text-slate-500 mt-1">{{ $summary['order_hari_ini'] }} order pengiriman</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Sedang Picking &amp; Packing</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($summary['picking_packing']) }} <span class="text-xs font-normal text-slate-500">Order</span></p>
            <p class="text-xs text-slate-500 mt-1">Zona A, B, dan C</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Siap di Loading Dock</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">{{ number_format($summary['siap_kirim']) }} <span class="text-xs font-normal text-slate-500">Order</span></p>
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
                        <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($outbounds as $row)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-900">{{ $row->outbound_number }}</td>
                            <td class="py-3.5 px-6 text-slate-700">{{ $row->destination_branch }}</td>
                            <td class="py-3.5 px-6 text-slate-700">{{ $row->inventoryItem?->name ?? '-' }}</td>
                            <td class="py-3.5 px-6 font-semibold">{{ number_format($row->quantity) }} {{ $row->unit }}</td>
                            <td class="py-3.5 px-6 text-slate-500">{{ $row->dock_location }}</td>
                            <td class="py-3.5 px-6">
                                @if ($row->status === 'terkirim')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">Terkirim</span>
                                @elseif ($row->status === 'siap_kirim')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">Siap Kirim</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">Picking &amp; Packing</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <form action="{{ route('barang-keluar.status', $row) }}" method="POST" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-xs">
                                        <option value="picking_packing" @selected($row->status === 'picking_packing')>Picking/Packing</option>
                                        <option value="siap_kirim" @selected($row->status === 'siap_kirim')>Siap Kirim</option>
                                        <option value="terkirim" @selected($row->status === 'terkirim')>Terkirim</option>
                                    </select>
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 cursor-pointer">Update</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 px-6 text-center text-xs text-slate-400">Belum ada data barang keluar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Buat Pengeluaran Barang -->
    <div id="outboundModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <h3 class="font-bold text-slate-900">Buat Pengeluaran Barang (Outbound)</h3>
                <button type="button" id="closeOutboundModalBtn" class="text-slate-400 hover:text-slate-700 cursor-pointer">&times;</button>
            </div>
            <form action="{{ route('barang-keluar.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">No. Outbound</label>
                        <input type="text" name="outbound_number" required value="OUT-{{ now()->format('Ymd') }}-{{ rand(10,99) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tujuan / Cabang</label>
                        <input type="text" name="destination_branch" required placeholder="Cabang Bandung Timur" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Barang / SKU</label>
                    <select name="inventory_item_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                        <option value="">-- Pilih SKU --</option>
                        @foreach ($items as $sku)
                            <option value="{{ $sku->id }}">{{ $sku->sku_code }} — {{ $sku->name }} (Stok: {{ $sku->stock }} {{ $sku->unit }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah</label>
                        <input type="number" name="quantity" min="1" required value="10" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Dock</label>
                        <input type="text" name="dock_location" required placeholder="Dock 01" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                        <select name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                            <option value="picking_packing">Picking &amp; Packing</option>
                            <option value="siap_kirim">Siap Kirim (Potong Stok)</option>
                            <option value="terkirim">Terkirim (Potong Stok)</option>
                        </select>
                    </div>
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" id="cancelOutboundModalBtn" class="px-4 py-2 rounded-xl border border-slate-200 text-sm text-slate-600 cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-500 cursor-pointer">Simpan Outbound</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const outModal = document.getElementById('outboundModal');
        document.getElementById('openOutboundModalBtn')?.addEventListener('click', () => { outModal.classList.remove('hidden'); outModal.classList.add('flex'); });
        ['closeOutboundModalBtn', 'cancelOutboundModalBtn'].forEach(id => document.getElementById(id)?.addEventListener('click', () => { outModal.classList.add('hidden'); outModal.classList.remove('flex'); }));
    </script>
@endsection