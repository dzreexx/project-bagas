@extends('layouts.app')

@section('title', 'Stok & Inventaris')
@section('page-title', 'Stok & Inventaris Gudang')

@section('content')
    <!-- Notifikasi Sukses -->
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm font-medium text-emerald-800">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Notifikasi Error Validasi -->
    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700">
            <p class="font-semibold mb-1">Gagal menyimpan data SKU:</p>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header Aksi Halaman -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500">Kelola daftar SKU, pantau sisa stok per rak, dan deteksi barang yang perlu restock.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button
                type="button"
                id="openExportModalBtn"
                class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 transition shadow-2xs inline-flex items-center gap-2 cursor-pointer"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Export CSV &amp; Template</span>
            </button>
            <button
                type="button"
                id="openCreateModalBtn"
                class="px-4 py-2 bg-indigo-600 rounded-xl text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm inline-flex items-center gap-2 cursor-pointer"
            >
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
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($summary['aman']) }} <span class="text-xs font-normal text-slate-500">SKU</span></p>
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
                <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($summary['menipis']) }} <span class="text-xs font-normal text-slate-500">SKU</span></p>
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
                <p class="text-2xl font-bold text-rose-600 mt-1">{{ number_format($summary['habis']) }} <span class="text-xs font-normal text-slate-500">SKU</span></p>
            </div>
            <span class="p-3 rounded-xl bg-rose-50 text-rose-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </span>
        </div>
    </div>

    <!-- Panel Filter & Pencarian -->
    <form method="GET" action="{{ route('stok') }}" class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div class="md:col-span-2 relative">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama barang, kode SKU, atau lokasi rak..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-4 py-2 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:outline-none"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <div>
                <select name="category" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    <option value="">Semua Kategori</option>
                    <option value="elektronik" @selected($category === 'elektronik')>Komponen Elektronik</option>
                    <option value="kemasan" @selected($category === 'kemasan')>Kemasan &amp; Karton</option>
                    <option value="sparepart" @selected($category === 'sparepart')>Sparepart Mesin</option>
                    <option value="bahan-baku" @selected($category === 'bahan-baku')>Bahan Baku</option>
                </select>
            </div>

            <div>
                <select name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    <option value="">Semua Status Stok</option>
                    <option value="aman" @selected($status === 'aman')>Stok Aman</option>
                    <option value="menipis" @selected($status === 'menipis')>Stok Menipis</option>
                    <option value="habis" @selected($status === 'habis')>Stok Habis</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition cursor-pointer">
                    Filter
                </button>
                @if ($search !== '' || $category !== '' || $status !== '')
                    <a href="{{ route('stok') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Tabel Daftar Stok & Inventaris -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="font-bold text-slate-900">Daftar Inventaris Barang</h2>
            <span class="text-xs text-slate-500">
                Menampilkan {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} SKU
            </span>
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
                    @forelse ($items as $item)
                        @php
                            $st = $item->stock_status;
                            $stockColor = match($st) {
                                'habis' => 'text-rose-600',
                                'menipis' => 'text-amber-600',
                                default => 'text-slate-900',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-6 font-mono text-xs font-semibold text-slate-700">{{ $item->sku_code }}</td>
                            <td class="py-3.5 px-6">
                                <div class="font-medium text-slate-900">{{ $item->name }}</div>
                                <div class="text-xs text-slate-400">
                                    {{ $item->batch_or_note ?: "Min. stok: {$item->min_stock} {$item->unit}" }}
                                </div>
                            </td>
                            <td class="py-3.5 px-6 text-slate-600">{{ $item->category_label }}</td>
                            <td class="py-3.5 px-6 text-slate-600">{{ $item->rack_location }}</td>
                            <td class="py-3.5 px-6 font-semibold {{ $stockColor }}">
                                {{ number_format($item->stock) }} {{ $item->unit }}
                            </td>
                            <td class="py-3.5 px-6">
                                @if ($st === 'habis')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Habis
                                    </span>
                                @elseif ($st === 'menipis')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menipis
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aman
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                <button
                                    type="button"
                                    data-item='@json($item)'
                                    data-update-url="{{ route('stok.update', $item) }}"
                                    class="edit-item-btn text-xs font-semibold text-indigo-600 hover:text-indigo-500 mr-3 cursor-pointer"
                                >
                                    Edit
                                </button>

                                <form action="{{ route('stok.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus SKU {{ $item->sku_code }} ({{ $item->name }})?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-500 cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 px-6 text-center text-sm text-slate-400">
                                Belum ada data barang SKU yang cocok. Klik <strong>Tambah Barang SKU</strong> untuk menambahkan data baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($items->hasPages())
            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL 1: TAMBAH / EDIT BARANG SKU -->
    <div id="skuModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <h3 id="skuModalTitle" class="font-bold text-slate-900">Tambah Barang SKU</h3>
                <button type="button" id="closeSkuModalBtn" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="skuForm" action="{{ route('stok.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="skuFormMethod" value="POST">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="skuCodeInput" class="block text-xs font-semibold text-slate-700 mb-1">Kode SKU</label>
                        <input id="skuCodeInput" name="sku_code" type="text" required placeholder="SKU-ELK-0099" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm font-mono focus:bg-white focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="categoryInput" class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                        <select id="categoryInput" name="category" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none">
                            <option value="elektronik">Komponen Elektronik</option>
                            <option value="kemasan">Kemasan &amp; Karton</option>
                            <option value="sparepart">Sparepart Mesin</option>
                            <option value="bahan-baku">Bahan Baku</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="nameInput" class="block text-xs font-semibold text-slate-700 mb-1">Nama Barang</label>
                    <input id="nameInput" name="name" type="text" required placeholder="Contoh: Sensor Barcode Handheld" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="rackInput" class="block text-xs font-semibold text-slate-700 mb-1">Lokasi Rak</label>
                        <input id="rackInput" name="rack_location" type="text" required placeholder="Zona A - Rak 04" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="batchInput" class="block text-xs font-semibold text-slate-700 mb-1">Batch / Catatan</label>
                        <input id="batchInput" name="batch_or_note" type="text" placeholder="Batch #B-2026-11" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label for="stockInput" class="block text-xs font-semibold text-slate-700 mb-1">Stok Saat Ini</label>
                        <input id="stockInput" name="stock" type="number" min="0" required value="0" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="minStockInput" class="block text-xs font-semibold text-slate-700 mb-1">Min. Stok</label>
                        <input id="minStockInput" name="min_stock" type="number" min="0" required value="10" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="unitInput" class="block text-xs font-semibold text-slate-700 mb-1">Satuan</label>
                        <input id="unitInput" name="unit" type="text" required value="Unit" placeholder="Box / Palet / Unit" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none">
                    </div>
                </div>

                <div class="pt-3 flex justify-end gap-2 border-t border-slate-100">
                    <button type="button" id="cancelSkuModalBtn" class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-500 transition cursor-pointer">
                        Simpan SKU
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EXPORT CSV & DOWNLOAD TEMPLATE + PANDUAN PENGISIAN -->
    <div id="exportModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4">
        <div class="w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900">Export Data CSV &amp; Template CSV</h3>
                    <p class="text-xs text-slate-500">Kompatibel dengan Microsoft Excel, Google Sheets, dan aplikasi spreadsheet.</p>
                </div>
                <button type="button" id="closeExportModalBtn" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-5 text-sm">
                <!-- Info Cara Pengisian CSV -->
                <div class="rounded-xl bg-indigo-50/70 border border-indigo-100 p-4 space-y-2">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-800">Panduan Cara Pengisian File CSV</h4>
                    <ul class="text-xs text-slate-700 space-y-1.5 list-disc list-inside">
                        <li><strong>Jangan mengubah nama header baris pertama:</strong> <code class="font-mono bg-white px-1 py-0.5 rounded border border-indigo-100">sku_code, name, category, rack_location, batch_or_note, stock, min_stock, unit</code>.</li>
                        <li><strong>sku_code:</strong> Kode unik barang (contoh: <code class="font-mono">SKU-ELK-0012</code>), tidak boleh duplikat.</li>
                        <li><strong>category:</strong> Gunakan salah satu kode: <code class="font-mono">elektronik</code>, <code class="font-mono">kemasan</code>, <code class="font-mono">sparepart</code>, atau <code class="font-mono">bahan-baku</code>.</li>
                        <li><strong>stock &amp; min_stock:</strong> Isi dengan angka bulat tanpa tanda koma/titik ribuan (contoh: <code class="font-mono">480</code> dan <code class="font-mono">50</code>).</li>
                        <li><strong>unit:</strong> Satuan barang, misalnya <code class="font-mono">Box</code>, <code class="font-mono">Palet</code>, <code class="font-mono">Unit</code>, atau <code class="font-mono">Roll</code>.</li>
                    </ul>
                </div>

                <!-- Tombol Download Template, Export Data & Import CSV -->
                <div class="space-y-4 pt-1">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                        <a
                            href="{{ route('stok.template-csv') }}"
                            class="px-4 py-2.5 rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-700 font-semibold text-xs sm:text-sm hover:bg-indigo-100 transition inline-flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Download Template CSV</span>
                        </a>

                        <a
                            href="{{ route('stok.export-csv', request()->only(['search', 'category', 'status'])) }}"
                            class="px-4 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold text-xs sm:text-sm hover:bg-emerald-500 transition shadow-sm inline-flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Export Data CSV</span>
                        </a>
                    </div>

                    <!-- Form & Tombol Import Data CSV -->
                    <form action="{{ route('stok.import-csv') }}" method="POST" enctype="multipart/form-data" class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        @csrf
                        <input
                            type="file"
                            name="csv_file"
                            accept=".csv,text/csv"
                            required
                            class="flex-1 text-xs text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer"
                        >
                        <button
                            type="submit"
                            class="px-4 py-2.5 rounded-xl bg-indigo-600 text-white font-semibold text-xs sm:text-sm hover:bg-indigo-500 transition shadow-sm inline-flex items-center justify-center gap-2 shrink-0 cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span>Import Data CSV</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Modal Tambah / Edit SKU
            const skuModal = document.getElementById('skuModal');
            const skuForm = document.getElementById('skuForm');
            const skuFormMethod = document.getElementById('skuFormMethod');
            const skuModalTitle = document.getElementById('skuModalTitle');

            const openCreateBtn = document.getElementById('openCreateModalBtn');
            const closeSkuBtn = document.getElementById('closeSkuModalBtn');
            const cancelSkuBtn = document.getElementById('cancelSkuModalBtn');

            const skuCodeInput = document.getElementById('skuCodeInput');
            const categoryInput = document.getElementById('categoryInput');
            const nameInput = document.getElementById('nameInput');
            const rackInput = document.getElementById('rackInput');
            const batchInput = document.getElementById('batchInput');
            const stockInput = document.getElementById('stockInput');
            const minStockInput = document.getElementById('minStockInput');
            const unitInput = document.getElementById('unitInput');

            function showModal(el) {
                el.classList.remove('hidden');
                el.classList.add('flex');
            }

            function hideModal(el) {
                el.classList.add('hidden');
                el.classList.remove('flex');
            }

            openCreateBtn?.addEventListener('click', () => {
                skuModalTitle.textContent = 'Tambah Barang SKU';
                skuForm.action = "{{ route('stok.store') }}";
                skuFormMethod.value = 'POST';
                skuCodeInput.value = '';
                categoryInput.value = 'elektronik';
                nameInput.value = '';
                rackInput.value = '';
                batchInput.value = '';
                stockInput.value = 0;
                minStockInput.value = 10;
                unitInput.value = 'Unit';
                showModal(skuModal);
            });

            document.querySelectorAll('.edit-item-btn').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const item = JSON.parse(btn.dataset.item);
                    skuModalTitle.textContent = `Edit Barang SKU — ${item.sku_code}`;
                    skuForm.action = btn.dataset.updateUrl;
                    skuFormMethod.value = 'PUT';
                    skuCodeInput.value = item.sku_code;
                    categoryInput.value = item.category;
                    nameInput.value = item.name;
                    rackInput.value = item.rack_location;
                    batchInput.value = item.batch_or_note || '';
                    stockInput.value = item.stock;
                    minStockInput.value = item.min_stock;
                    unitInput.value = item.unit;
                    showModal(skuModal);
                });
            });

            closeSkuBtn?.addEventListener('click', () => hideModal(skuModal));
            cancelSkuBtn?.addEventListener('click', () => hideModal(skuModal));
            skuModal?.addEventListener('click', (e) => {
                if (e.target === skuModal) hideModal(skuModal);
            });

            // Modal Export CSV & Template
            const exportModal = document.getElementById('exportModal');
            const openExportBtn = document.getElementById('openExportModalBtn');
            const closeExportBtn = document.getElementById('closeExportModalBtn');

            openExportBtn?.addEventListener('click', () => showModal(exportModal));
            closeExportBtn?.addEventListener('click', () => hideModal(exportModal));
            exportModal?.addEventListener('click', (e) => {
                if (e.target === exportModal) hideModal(exportModal);
            });
        });
    </script>
@endsection