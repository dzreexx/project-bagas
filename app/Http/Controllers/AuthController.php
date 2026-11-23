<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'], // bisa email atau ID karyawan
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Alamat email atau ID karyawan wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = trim($credentials['login']);
        $user = User::where('email', strtolower($loginInput))
            ->orWhere('employee_id', strtoupper($loginInput))
            ->first();

        // Jika user ditemukan dan sudah Nonaktif
        if ($user && $user->status !== 'Aktif') {
            return back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors([
                    'login' => 'Akun ini berstatus Nonaktif. Silakan hubungi IT Support untuk mengaktifkan kembali.',
                ]);
        }

        // Cek password jika user ditemukan
        if ($user && Hash::check($credentials['password'], $user->password)) {
            $user->update([
                'failed_login_attempts' => 0,
                'last_login_at' => now(),
            ]);

            $remember = $request->boolean('remember');
            Auth::login($user, $remember);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Jika login gagal & user ada di database -> tambah hitungan gagal (maks 10x)
        if ($user) {
            $attempts = $user->failed_login_attempts + 1;

            if ($attempts >= 10) {
                $user->update([
                    'failed_login_attempts' => 10,
                    'status' => 'Nonaktif',
                ]);

                if (class_exists(ActivityLog::class)) {
                    ActivityLog::record(
                        title: "Akun {$user->name} dinonaktifkan otomatis",
                        description: "10x percobaan login gagal ({$user->email})",
                        type: 'warning'
                    );
                }

                return back()
                    ->withInput($request->only('login', 'remember'))
                    ->withErrors([
                        'login' => 'Akun Anda telah dinonaktifkan setelah 10 kali percobaan login gagal. Hubungi IT Support.',
                    ]);
            }

            $user->update([
                'failed_login_attempts' => $attempts,
            ]);

            $sisa = 10 - $attempts;

            return back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors([
                    'login' => "Email/ID atau kata sandi salah. Sisa percobaan sebelum akun dinonaktifkan: {$sisa}x (Gagal {$attempts}/10).",
                ]);
        }

        // Jika email/ID tidak ditemukan
        return back()
            ->withInput($request->only('login', 'remember'))
            ->withErrors([
                'login' => 'Email/ID karyawan atau kata sandi salah.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Masukkan alamat email terdaftar.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);

        // Catat permintaan reset password ke log jika tersedia
        if (class_exists(ActivityLog::class)) {
            ActivityLog::record(
                title: "Permintaan reset kata sandi",
                description: "Email: " . strtolower(trim($request->email)),
                type: 'info'
            );
        }

        return back()
            ->withInput()
            ->with('status', 'Jika email terdaftar di sistem, instruksi / permintaan reset kata sandi telah diproses dan diteruskan ke IT Support.');
    }
}