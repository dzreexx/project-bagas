<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    // Helper statis untuk mencatat log baru dari mana saja (controller, event, dll.)
    public static function record(string $title, ?string $description = null, string $type = 'info'): self
    {
        return self::create([
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'is_read' => false,
        ]);
    }
}