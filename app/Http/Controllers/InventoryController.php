<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $category = (string) $request->query('category', '');
        $status = (string) $request->query('status', '');

        $query = InventoryItem::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('sku_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('rack_location', 'like', "%{$search}%")
                    ->orWhere('batch_or_note', 'like', "%{$search}%");
            });
        }

        if ($category !== '') {
            $query->where('category', $category);
        }

        if ($status === 'habis') {
            $query->where('stock', '<=', 0);
        } elseif ($status === 'menipis') {
            $query->where('stock', '>', 0)
                ->whereColumn('stock', '<=', 'min_stock');
        } elseif ($status === 'aman') {
            $query->whereColumn('stock', '>', 'min_stock');
        }

        $items = $query->latest('id')->paginate(10)->withQueryString();

        $allItems = InventoryItem::select('stock', 'min_stock')->get();
        $summary = [
            'aman' => $allItems->filter(fn ($i) => $i->stock > $i->min_stock)->count(),
            'menipis' => $allItems->filter(fn ($i) => $i->stock > 0 && $i->stock <= $i->min_stock)->count(),
            'habis' => $allItems->filter(fn ($i) => $i->stock <= 0)->count(),
        ];

        return view('stok', compact('items', 'summary', 'search', 'category', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku_code' => ['required', 'string', 'max:60', 'unique:inventory_items,sku_code'],
            'name' => ['required', 'string', 'max:200'],
            'category' => ['required', Rule::in(['elektronik', 'kemasan', 'sparepart', 'bahan-baku'])],
            'rack_location' => ['required', 'string', 'max:120'],
            'batch_or_note' => ['nullable', 'string', 'max:120'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:30'],
        ], [
            'sku_code.required' => 'Kode SKU wajib diisi.',
            'sku_code.unique' => 'Kode SKU sudah terdaftar.',
            'name.required' => 'Nama barang wajib diisi.',
            'rack_location.required' => 'Lokasi rak wajib diisi.',
        ]);

        $skuCode = strtoupper(trim($validated['sku_code']));

        $item = InventoryItem::create([
            'sku_code' => $skuCode,
            'name' => $validated['name'],
            'category' => $validated['category'],
            'rack_location' => $validated['rack_location'],
            'batch_or_note' => $validated['batch_or_note'] ?: null,
            'stock' => $validated['stock'],
            'min_stock' => $validated['min_stock'],
            'unit' => $validated['unit'],
        ]);

        if (class_exists(ActivityLog::class)) {
            ActivityLog::record(
                title: "SKU baru ditambahkan: {$item->sku_code}",
                description: "{$item->name} ({$item->stock} {$item->unit})",
                type: 'success'
            );
        }

        return back()->with('success', "Barang SKU {$item->sku_code} berhasil ditambahkan.");
    }

    public function update(Request $request, InventoryItem $item)
    {
        $validated = $request->validate([
            'sku_code' => ['required', 'string', 'max:60', Rule::unique('inventory_items', 'sku_code')->ignore($item->id)],
            'name' => ['required', 'string', 'max:200'],
            'category' => ['required', Rule::in(['elektronik', 'kemasan', 'sparepart', 'bahan-baku'])],
            'rack_location' => ['required', 'string', 'max:120'],
            'batch_or_note' => ['nullable', 'string', 'max:120'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:30'],
        ]);

        $item->update([
            'sku_code' => strtoupper(trim($validated['sku_code'])),
            'name' => $validated['name'],
            'category' => $validated['category'],
            'rack_location' => $validated['rack_location'],
            'batch_or_note' => $validated['batch_or_note'] ?: null,
            'stock' => $validated['stock'],
            'min_stock' => $validated['min_stock'],
            'unit' => $validated['unit'],
        ]);

        if (class_exists(ActivityLog::class)) {
            ActivityLog::record(
                title: "SKU diperbarui: {$item->sku_code}",
                description: "{$item->name} — Stok saat ini: {$item->stock} {$item->unit}",
                type: 'info'
            );
        }

        return back()->with('success', "Data SKU {$item->sku_code} berhasil diperbarui.");
    }

    public function destroy(InventoryItem $item)
    {
        $code = $item->sku_code;
        $name = $item->name;
        $item->delete();

        if (class_exists(ActivityLog::class)) {
            ActivityLog::record(
                title: "SKU dihapus: {$code}",
                description: $name,
                type: 'warning'
            );
        }

        return back()->with('success', "Barang SKU {$code} berhasil dihapus.");
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $search = trim((string) $request->query('search', ''));
        $category = (string) $request->query('category', '');
        $status = (string) $request->query('status', '');

        $query = InventoryItem::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('sku_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('rack_location', 'like', "%{$search}%");
            });
        }

        if ($category !== '') {
            $query->where('category', $category);
        }

        if ($status === 'habis') {
            $query->where('stock', '<=', 0);
        } elseif ($status === 'menipis') {
            $query->where('stock', '>', 0)->whereColumn('stock', '<=', 'min_stock');
        } elseif ($status === 'aman') {
            $query->whereColumn('stock', '>', 'min_stock');
        }

        $rows = $query->orderBy('sku_code')->get();
        $filename = 'stok-inventaris-' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM agar karakter dan pemisah terbaca rapi saat dibuka di Microsoft Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'sku_code',
                'name',
                'category',
                'rack_location',
                'batch_or_note',
                'stock',
                'min_stock',
                'unit',
                'status_stok',
            ]);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->sku_code,
                    $row->name,
                    $row->category,
                    $row->rack_location,
                    $row->batch_or_note ?? '',
                    $row->stock,
                    $row->min_stock,
                    $row->unit,
                    strtoupper($row->stock_status),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function downloadTemplateCsv(): StreamedResponse
    {
        $filename = 'template-stok-inventaris.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'sku_code',
                'name',
                'category',
                'rack_location',
                'batch_or_note',
                'stock',
                'min_stock',
                'unit',
            ]);

            // Contoh baris pengisian
            fputcsv($handle, [
                'SKU-ELK-0012',
                'Komponen Elektronik A2',
                'elektronik',
                'Zona A - Rak 04',
                'Batch #B-2026-11',
                '480',
                '50',
                'Box',
            ]);

            fputcsv($handle, [
                'SKU-KMS-0089',
                'Kemasan Karton Industrial',
                'kemasan',
                'Zona C - Rak 02',
                'Min. stok 100 palet',
                '35',
                '100',
                'Palet',
            ]);

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ], [
            'csv_file.required' => 'Pilih file CSV terlebih dahulu.',
            'csv_file.mimes' => 'Format file harus .csv atau .txt.',
            'csv_file.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return back()->withErrors(['csv_file' => 'File CSV tidak dapat dibaca.']);
        }

        // Bersihkan UTF-8 BOM jika ada di baris pertama
        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);
            return back()->withErrors(['csv_file' => 'File CSV kosong.']);
        }

        $cleanFirstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);
        $delimiter = substr_count($cleanFirstLine, ';') > substr_count($cleanFirstLine, ',') ? ';' : ',';
        $headers = array_map(fn ($h) => strtolower(trim($h)), str_getcsv($cleanFirstLine, $delimiter));

        $expectedHeaders = ['sku_code', 'name', 'category', 'rack_location', 'batch_or_note', 'stock', 'min_stock', 'unit'];
        if ($headers !== $expectedHeaders) {
            fclose($handle);
            return back()->withErrors([
                'csv_file' => 'Header CSV tidak sesuai template. Gunakan urutan: ' . implode(', ', $expectedHeaders),
            ]);
        }

        $importedCount = 0;
        $skippedErrors = [];
        $rowNumber = 1;

        while (($line = fgets($handle)) !== false) {
            $rowNumber++;
            if (trim($line) === '') {
                continue;
            }

            $cells = str_getcsv($line, $delimiter);
            if (count($cells) < count($expectedHeaders)) {
                $skippedErrors[] = "Baris {$rowNumber}: jumlah kolom kurang.";
                continue;
            }

            $row = [
                'sku_code' => strtoupper(trim((string) ($cells[0] ?? ''))),
                'name' => trim((string) ($cells[1] ?? '')),
                'category' => strtolower(trim((string) ($cells[2] ?? ''))),
                'rack_location' => trim((string) ($cells[3] ?? '')),
                'batch_or_note' => trim((string) ($cells[4] ?? '')) ?: null,
                'stock' => trim((string) ($cells[5] ?? '0')),
                'min_stock' => trim((string) ($cells[6] ?? '10')),
                'unit' => trim((string) ($cells[7] ?? 'Unit')) ?: 'Unit',
            ];

            $validator = Validator::make($row, [
                'sku_code' => ['required', 'string', 'max:60'],
                'name' => ['required', 'string', 'max:200'],
                'category' => ['required', Rule::in(['elektronik', 'kemasan', 'sparepart', 'bahan-baku'])],
                'rack_location' => ['required', 'string', 'max:120'],
                'batch_or_note' => ['nullable', 'string', 'max:120'],
                'stock' => ['required', 'integer', 'min:0'],
                'min_stock' => ['required', 'integer', 'min:0'],
                'unit' => ['required', 'string', 'max:30'],
            ]);

            if ($validator->fails()) {
                $skippedErrors[] = "Baris {$rowNumber} ({$row['sku_code']}): " . implode(', ', $validator->errors()->all());
                continue;
            }

            InventoryItem::updateOrCreate(
                ['sku_code' => $row['sku_code']],
                [
                    'name' => $row['name'],
                    'category' => $row['category'],
                    'rack_location' => $row['rack_location'],
                    'batch_or_note' => $row['batch_or_note'],
                    'stock' => (int) $row['stock'],
                    'min_stock' => (int) $row['min_stock'],
                    'unit' => $row['unit'],
                ]
            );

            $importedCount++;
        }

        fclose($handle);

        if ($importedCount > 0 && class_exists(ActivityLog::class)) {
            ActivityLog::record(
                title: "Import CSV stok inventaris ({$importedCount} SKU)",
                description: "Diproses oleh " . (auth()->user()?->name ?? 'Sistem'),
                type: 'success'
            );
        }

        if (!empty($skippedErrors)) {
            return back()->with(
                'success',
                "Import selesai: {$importedCount} SKU berhasil diproses. Beberapa baris dilewati: " . implode(' | ', array_slice($skippedErrors, 0, 3))
            );
        }

        return back()->with('success', "Import CSV berhasil: {$importedCount} data SKU disimpan ke database.");
    }
}