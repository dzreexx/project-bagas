@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk (Inbound)')

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
            <p class="text-sm text-slate-500">Catat penerimaan barang dari supplier, verifikasi surat jalan, dan pantau proses QC.</p>
        </div>
        <button type="button" id="openInboundModalBtn" class="px-4 py-2 bg-indigo-600 rounded-xl text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm inline-flex items-center gap-2 self-start sm:self-auto cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Input Barang Masuk</span>
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Masuk Hari Ini</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($summary['masuk_hari_ini']) }} <span class="text-xs font-normal text-slate-500">Qty</span></p>
            <p class="text-xs text-emerald-600 font-medium mt-1">{{ $summary['surat_jalan_hari_ini'] }} Surat Jalan diproses</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Menunggu QC</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($summary['menunggu_qc']) }} <span class="text-xs font-normal text-slate-500">Dokumen</span></p>
            <p class="text-xs text-slate-500 mt-1">Zona Receiving &amp; Inspeksi</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Selesai Put-Away</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($summary['selesai_put_away']) }} <span class="text-xs font-normal text-slate-500">Qty</span></p>
            <p class="text-xs text-slate-500 mt-1">Sudah masuk ke stok &amp; rak tujuan</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="font-bold text-slate-900">Daftar Penerimaan Barang</h2>
            <span class="text-xs text-slate-500">{{ now()->translatedFormat('d M Y') }}</span>
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
                        <th class="py-3 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($inbounds as $row)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-900">{{ $row->inbound_number }}</td>
                            <td class="py-3.5 px-6 text-slate-700">{{ $row->supplier_name }}</td>
                            <td class="py-3.5 px-6 text-slate-700">
                                <div class="font-medium text-slate-900">{{ $row->inventoryItem?->name ?? '-' }}</div>
                                <div class="text-xs font-mono text-slate-400">{{ $row->inventoryItem?->sku_code ?? '' }}</div>
                            </td>
                            <td class="py-3.5 px-6 font-semibold">{{ number_format($row->quantity) }} {{ $row->unit }}</td>
                            <td class="py-3.5 px-6 text-slate-500">{{ $row->target_rack }}</td>
                            <td class="py-3.5 px-6">
                                @if ($row->status === 'selesai')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">Selesai</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">Menunggu QC</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                @if ($row->status === 'menunggu_qc')
                                    <form action="{{ route('barang-masuk.status', $row) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="px-3 py-1 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-500 cursor-pointer">
                                            Selesaikan QC &amp; Masuk Stok
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">Stok Tercatat</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 px-6 text-center text-xs text-slate-400">Belum ada data barang masuk. Klik <strong>Input Barang Masuk</strong>.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Input Barang Masuk -->
    <div id="inboundModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <h3 class="font-bold text-slate-900">Input Barang Masuk (Inbound)</h3>
                <button type="button" id="closeInboundModalBtn" class="text-slate-400 hover:text-slate-700 cursor-pointer">&times;</button>
            </div>
            <form action="{{ route('barang-masuk.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">No. Inbound</label>
                        <input type="text" name="inbound_number" required value="IN-{{ now()->format('Ymd') }}-{{ rand(10,99) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Supplier</label>
                        <input type="text" name="supplier_name" required placeholder="PT Elektro Jaya Abadi" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm">
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
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tujuan Rak</label>
                        <input type="text" name="target_rack" required placeholder="Zona A - Rak 04" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status Awal</label>
                        <select name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                            <option value="menunggu_qc">Menunggu QC</option>
                            <option value="selesai">Selesai (Tambah Stok)</option>
                        </select>
                    </div>
                </div>
                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" id="cancelInboundModalBtn" class="px-4 py-2 rounded-xl border border-slate-200 text-sm text-slate-600 cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-500 cursor-pointer">Simpan Inbound</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const inModal = document.getElementById('inboundModal');
        document.getElementById('openInboundModalBtn')?.addEventListener('click', () => { inModal.classList.remove('hidden'); inModal.classList.add('flex'); });
        ['closeInboundModalBtn', 'cancelInboundModalBtn'].forEach(id => document.getElementById(id)?.addEventListener('click', () => { inModal.classList.add('hidden'); inModal.classList.remove('flex'); }));
    </script>
@endsection