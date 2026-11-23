<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'password',
        'role',
        'status',
        'failed_login_attempts',
        'last_login_at',
        'warehouse_name',
        'warehouse_code',
        'low_stock_threshold',
        'theme_mode',
        'font_size',
        'accent_color',
        'compact_mode',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'low_stock_threshold' => 'integer',
            'compact_mode' => 'boolean',
        ];
    }
}