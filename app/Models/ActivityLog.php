<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'actor_name',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(
        string $title,
        ?string $description = null,
        string $type = 'info',
        ?User $actor = null
    ): self {
        /** @var User|null $user */
        $user = $actor ?? Auth::user();

        $actorLabel = $user
            ? ($user->name . (!empty($user->role) ? " ({$user->role})" : ''))
            : 'Sistem';

        return self::create([
            'user_id' => $user?->id,
            'actor_name' => $actorLabel,
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'is_read' => false,
        ]);
    }
}