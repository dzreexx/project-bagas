<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        return view('pengaturan', [
            'user' => auth()->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'warehouse_name' => ['required', 'string', 'max:150'],
            'warehouse_code' => ['required', 'string', 'max:50'],
            'low_stock_threshold' => ['required', 'integer', 'min:1', 'max:100'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'low_stock_threshold.min' => 'Ambang batas minimal 1%.',
            'low_stock_threshold.max' => 'Ambang batas maksimal 100%.',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'warehouse_name' => $validated['warehouse_name'],
            'warehouse_code' => strtoupper(trim($validated['warehouse_code'])),
            'low_stock_threshold' => $validated['low_stock_threshold'],
        ]);

        if (class_exists(ActivityLog::class)) {
            ActivityLog::record(
                title: "Profil & konfigurasi gudang diperbarui",
                description: "Oleh {$user->name} ({$user->warehouse_code})",
                type: 'info'
            );
        }

        return back()->with('success', 'Profil akun dan konfigurasi gudang berhasil disimpan.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai.'])
                ->with('active_tab', 'security');
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        if (class_exists(ActivityLog::class)) {
            ActivityLog::record(
                title: "Kata sandi akun diubah",
                description: "User: {$user->name} ({$user->email})",
                type: 'warning'
            );
        }

        return back()
            ->with('success', 'Kata sandi berhasil diperbarui.')
            ->with('active_tab', 'security');
    }

    public function updateAppearance(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'theme_mode' => ['required', Rule::in(['light', 'dark'])],
            'font_size' => ['required', Rule::in(['sm', 'base', 'lg'])],
            'accent_color' => ['required', Rule::in(['indigo', 'emerald', 'sky'])],
            'compact_mode' => ['nullable', 'boolean'],
        ]);

        $user->update([
            'theme_mode' => $validated['theme_mode'],
            'font_size' => $validated['font_size'],
            'accent_color' => $validated['accent_color'],
            'compact_mode' => $request->boolean('compact_mode'),
        ]);

        return back()
            ->with('success', 'Preferensi tampilan & sistem berhasil disimpan.')
            ->with('active_tab', 'appearance');
    }
}