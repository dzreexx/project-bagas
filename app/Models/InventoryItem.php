<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
        'sku_code',
        'name',
        'category',
        'rack_location',
        'batch_or_note',
        'stock',
        'min_stock',
        'unit',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'min_stock' => 'integer',
        ];
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'habis';
        }

        if ($this->stock <=$this->min_stock) {
            return 'menipis';
        }

        return 'aman';
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'elektronik' => 'Komponen Elektronik',
            'kemasan' => 'Kemasan & Karton',
            'sparepart' => 'Sparepart Mesin',
            'bahan-baku' => 'Bahan Baku',
            default => ucfirst($this->category),
        };
    }
}