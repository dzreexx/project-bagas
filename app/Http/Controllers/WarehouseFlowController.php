<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Fleet;
use App\Models\InboundOrder;
use App\Models\InventoryItem;
use App\Models\OutboundOrder;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WarehouseFlowController extends Controller
{
    // ================= BARANG MASUK (INBOUND) =================
    public function inboundIndex()
    {
        $items = InventoryItem::orderBy('name')->get();
        $inbounds = InboundOrder::with(['inventoryItem', 'creator'])->latest('id')->get();

        $todayInbounds = InboundOrder::whereDate('created_at', today())->get();
        $summary = [
            'masuk_hari_ini' => $todayInbounds->sum('quantity'),
            'surat_jalan_hari_ini' => $todayInbounds->count(),
            'menunggu_qc' => InboundOrder::where('status', 'menunggu_qc')->count(),
            'selesai_put_away' => InboundOrder::where('status', 'selesai')->sum('quantity'),
        ];

        return view('barang-masuk', compact('items', 'inbounds', 'summary'));
    }

    public function inboundStore(Request $request)
    {
        $validated = $request->validate([
            'inbound_number' => ['required', 'string', 'max:60', 'unique:inbound_orders,inbound_number'],
            'supplier_name' => ['required', 'string', 'max:150'],
            'inventory_item_id' => ['required', 'exists:inventory_items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'target_rack' => ['required', 'string', 'max:120'],
            'status' => ['required', Rule::in(['menunggu_qc', 'selesai'])],
        ]);

        DB::transaction(function () use ($validated) {
            $item = InventoryItem::lockForUpdate()->findOrFail($validated['inventory_item_id']);
            $markDone = $validated['status'] === 'selesai';

            $inbound = InboundOrder::create([
                'inbound_number' => strtoupper(trim($validated['inbound_number'])),
                'supplier_name' => $validated['supplier_name'],
                'inventory_item_id' => $item->id,
                'quantity' => $validated['quantity'],
                'unit' => $item->unit,
                'target_rack' => $validated['target_rack'],
                'status' => $validated['status'],
                'stock_applied' => $markDone,
                'created_by' => auth()->id(),
            ]);

            if ($markDone) {
                $item->increment('stock', $inbound->quantity);
                $item->update(['rack_location' => $inbound->target_rack]);
            }

            if (class_exists(ActivityLog::class)) {
                ActivityLog::record(
                    title: "Barang masuk dicatat: {$inbound->inbound_number}",
                    description: "{$item->name} (+{$inbound->quantity} {$item->unit}) dari {$inbound->supplier_name}",
                    type: $markDone ? 'success' : 'info'
                );
            }
        });

        return back()->with('success', 'Data barang masuk berhasil disimpan.');
    }

    public function inboundUpdateStatus(Request $request, InboundOrder $inbound)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['menunggu_qc', 'selesai'])],
        ]);

        DB::transaction(function () use ($validated, $inbound) {
            $inbound->load('inventoryItem');
            $newStatus = $validated['status'];

            if ($newStatus === 'selesai' && !$inbound->stock_applied && $inbound->inventoryItem) {
                $inbound->inventoryItem->increment('stock', $inbound->quantity);
                $inbound->inventoryItem->update(['rack_location' => $inbound->target_rack]);
                $inbound->stock_applied = true;
            }

            $inbound->status = $newStatus;
            $inbound->save();

            if (class_exists(ActivityLog::class)) {
                ActivityLog::record(
                    title: "Status inbound {$inbound->inbound_number}: " . ($newStatus === 'selesai' ? 'Selesai' : 'Menunggu QC'),
                    description: ($inbound->inventoryItem?->name ?? 'SKU') . " ({$inbound->quantity} {$inbound->unit})",
                    type: $newStatus === 'selesai' ? 'success' : 'warning'
                );
            }
        });

        return back()->with('success', "Status {$inbound->inbound_number} diperbarui.");
    }

    // ================= BARANG KELUAR (OUTBOUND) =================
    public function outboundIndex()
    {
        $items = InventoryItem::orderBy('name')->get();
        $outbounds = OutboundOrder::with(['inventoryItem', 'creator'])->latest('id')->get();

        $todayOutbounds = OutboundOrder::whereDate('created_at', today())->get();
        $summary = [
            'keluar_hari_ini' => $todayOutbounds->sum('quantity'),
            'order_hari_ini' => $todayOutbounds->count(),
            'picking_packing' => OutboundOrder::where('status', 'picking_packing')->count(),
            'siap_kirim' => OutboundOrder::where('status', 'siap_kirim')->count(),
        ];

        return view('barang-keluar', compact('items', 'outbounds', 'summary'));
    }

    public function outboundStore(Request $request)
    {
        $validated = $request->validate([
            'outbound_number' => ['required', 'string', 'max:60', 'unique:outbound_orders,outbound_number'],
            'destination_branch' => ['required', 'string', 'max:150'],
            'inventory_item_id' => ['required', 'exists:inventory_items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'dock_location' => ['required', 'string', 'max:80'],
            'status' => ['required', Rule::in(['picking_packing', 'siap_kirim', 'terkirim'])],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $item = InventoryItem::lockForUpdate()->findOrFail($validated['inventory_item_id']);
                $shouldDeduct = in_array($validated['status'], ['siap_kirim', 'terkirim'], true);

                if ($shouldDeduct && $item->stock < $validated['quantity']) {
                    throw new \RuntimeException("Stok {$item->sku_code} tidak mencukupi (tersedia: {$item->stock} {$item->unit}).");
                }

                $outbound = OutboundOrder::create([
                    'outbound_number' => strtoupper(trim($validated['outbound_number'])),
                    'destination_branch' => $validated['destination_branch'],
                    'inventory_item_id' => $item->id,
                    'quantity' => $validated['quantity'],
                    'unit' => $item->unit,
                    'dock_location' => $validated['dock_location'],
                    'status' => $validated['status'],
                    'stock_deducted' => $shouldDeduct,
                    'created_by' => auth()->id(),
                ]);

                if ($shouldDeduct) {
                    $item->decrement('stock', $outbound->quantity);
                }

                if (class_exists(ActivityLog::class)) {
                    ActivityLog::record(
                        title: "Barang keluar dibuat: {$outbound->outbound_number}",
                        description: "{$item->name} (-{$outbound->quantity} {$item->unit}) ke {$outbound->destination_branch}",
                        type: 'info'
                    );
                }
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['quantity' => $e->getMessage()])->withInput();
        }

        return back()->with('success', 'Pengeluaran barang (outbound) berhasil dicatat.');
    }

    public function outboundUpdateStatus(Request $request, OutboundOrder $outbound)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['picking_packing', 'siap_kirim', 'terkirim'])],
        ]);

        try {
            DB::transaction(function () use ($validated, $outbound) {
                $outbound->load('inventoryItem');
                $newStatus = $validated['status'];
                $shouldDeduct = in_array($newStatus, ['siap_kirim', 'terkirim'], true);

                if ($shouldDeduct && !$outbound->stock_deducted && $outbound->inventoryItem) {
                    $item = InventoryItem::lockForUpdate()->findOrFail($outbound->inventory_item_id);
                    if ($item->stock < $outbound->quantity) {
                        throw new \RuntimeException("Stok {$item->sku_code} tidak mencukupi (tersedia: {$item->stock} {$item->unit}).");
                    }
                    $item->decrement('stock', $outbound->quantity);
                    $outbound->stock_deducted = true;
                }

                $outbound->status = $newStatus;
                $outbound->save();

                if (class_exists(ActivityLog::class)) {
                    ActivityLog::record(
                        title: "Status outbound {$outbound->outbound_number}: " . str_replace('_', ' ', ucfirst($newStatus)),
                        description: ($outbound->inventoryItem?->name ?? 'SKU') . " -> {$outbound->destination_branch}",
                        type: 'info'
                    );
                }
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return back()->with('success', "Status {$outbound->outbound_number} diperbarui.");
    }

    // ================= PENGIRIMAN & ARMADA =================
    public function shipmentIndex()
    {
        if (Fleet::count() === 0) {
            Fleet::insert([
                ['vehicle_name' => 'Wingbox B 9241 KYT', 'plate_number' => 'B 9241 KYT', 'driver_name' => 'Hendra Wijaya', 'status' => 'dalam_perjalanan'],
                ['vehicle_name' => 'Box Engkel B 8810 URA', 'plate_number' => 'B 8810 URA', 'driver_name' => 'Rudi Santoso', 'status' => 'standby'],
                ['vehicle_name' => 'Tronton Box B 9512 TDA', 'plate_number' => 'B 9512 TDA', 'driver_name' => 'Dedi Kurniawan', 'status' => 'standby'],
                ['vehicle_name' => 'Blind Van B 1940 FGH', 'plate_number' => 'B 1940 FGH', 'driver_name' => 'Agus Pratama', 'status' => 'perawatan'],
            ]);
        }

        $fleets = Fleet::orderBy('vehicle_name')->get();
        $outbounds = OutboundOrder::with('inventoryItem')->whereIn('status', ['picking_packing', 'siap_kirim'])->latest('id')->get();
        $shipments = Shipment::with(['fleet', 'outboundOrder.inventoryItem', 'creator'])->latest('id')->get();

        $summary = [
            'dalam_perjalanan' => Shipment::where('status', 'dalam_perjalanan')->count() + Fleet::where('status', 'dalam_perjalanan')->count(),
            'standby' => Fleet::where('status', 'standby')->count(),
            'perawatan' => Fleet::where('status', 'perawatan')->count(),
        ];

        return view('pengiriman', compact('fleets', 'outbounds', 'shipments', 'summary'));
    }

    public function shipmentStore(Request $request)
    {
        $validated = $request->validate([
            'shipment_number' => ['required', 'string', 'max:60', 'unique:shipments,shipment_number'],
            'outbound_order_id' => ['nullable', 'exists:outbound_orders,id'],
            'fleet_id' => ['required', 'exists:fleets,id'],
            'destination' => ['required', 'string', 'max:150'],
            'eta_label' => ['required', 'string', 'max:100'],
            'status' => ['required', Rule::in(['dalam_perjalanan', 'terkirim'])],
        ]);

        DB::transaction(function () use ($validated) {
            $fleet = Fleet::findOrFail($validated['fleet_id']);

            $shipment = Shipment::create([
                'shipment_number' => strtoupper(trim($validated['shipment_number'])),
                'outbound_order_id' => $validated['outbound_order_id'] ?: null,
                'fleet_id' => $fleet->id,
                'destination' => $validated['destination'],
                'eta_label' => $validated['eta_label'],
                'status' => $validated['status'],
                'created_by' => auth()->id(),
            ]);

            if ($validated['status'] === 'dalam_perjalanan') {
                $fleet->update(['status' => 'dalam_perjalanan']);
                if ($shipment->outboundOrder) {
                    $shipment->outboundOrder->update(['status' => 'siap_kirim']);
                }
            } else {
                $fleet->update(['status' => 'standby']);
                if ($shipment->outboundOrder) {
                    $shipment->outboundOrder->update(['status' => 'terkirim']);
                }
            }

            if (class_exists(ActivityLog::class)) {
                ActivityLog::record(
                    title: "Pengiriman dijadwalkan: {$shipment->shipment_number}",
                    description: "{$fleet->vehicle_name} ({$fleet->driver_name}) -> {$shipment->destination}",
                    type: 'info'
                );
            }
        });

        return back()->with('success', 'Jadwal pengiriman & penugasan armada berhasil disimpan.');
    }

    public function shipmentUpdateStatus(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['dalam_perjalanan', 'terkirim'])],
        ]);

        DB::transaction(function () use ($validated, $shipment) {
            $shipment->load(['fleet', 'outboundOrder']);
            $shipment->update(['status' => $validated['status']]);

            if ($shipment->fleet) {
                $shipment->fleet->update([
                    'status' => $validated['status'] === 'terkirim' ? 'standby' : 'dalam_perjalanan',
                ]);
            }

            if ($shipment->outboundOrder && $validated['status'] === 'terkirim') {
                $shipment->outboundOrder->update(['status' => 'terkirim']);
            }

            if (class_exists(ActivityLog::class)) {
                ActivityLog::record(
                    title: "Pengiriman {$shipment->shipment_number}: " . ($validated['status'] === 'terkirim' ? 'Terkirim' : 'Dalam Perjalanan'),
                    description: ($shipment->fleet?->vehicle_name ?? 'Armada') . " -> {$shipment->destination}",
                    type: $validated['status'] === 'terkirim' ? 'success' : 'info'
                );
            }
        });

        return back()->with('success', "Status pengiriman {$shipment->shipment_number} diperbarui.");
    }

    public function fleetUpdateStatus(Request $request, Fleet $fleet)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['standby', 'dalam_perjalanan', 'perawatan'])],
        ]);

        $fleet->update(['status' => $validated['status']]);

        if (class_exists(ActivityLog::class)) {
            ActivityLog::record(
                title: "Status armada {$fleet->vehicle_name}: " . str_replace('_', ' ', ucfirst($validated['status'])),
                description: "Driver: {$fleet->driver_name}",
                type: $validated['status'] === 'perawatan' ? 'warning' : 'info'
            );
        }

        return back()->with('success', "Status armada {$fleet->vehicle_name} diperbarui.");
    }
}