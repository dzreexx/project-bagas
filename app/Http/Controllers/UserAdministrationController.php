<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;

class UserAdministrationController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->get();

        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'Aktif')->count(),
            'inactive' => User::where('status', 'Nonaktif')->count(),
            'admin' => User::whereIn('role', ['IT Administrator', 'Kepala Gudang'])->count(),
        ];

        return view('user-administration', compact('users', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'employee_id' => ['required', 'string', 'max:50', 'unique:users,employee_id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:Aktif,Nonaktif'],
        ]);

        User::create([
            'name' => $validated['name'],
            'employee_id' => strtoupper($validated['employee_id']),
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);
        
        ActivityLog::record(
            title: "User baru ditambahkan: {$validated['name']}",
            description: "ID {$validated['employee_id']} dengan role {$validated['role']}",
            type: 'success'
        );

        return redirect()
            ->route('user-administration')
            ->with('success', "Akun {$validated['name']} ({$validated['role']}) berhasil disimpan ke database!");

    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'Aktif' ? 'Nonaktif' : 'Aktif',
        ]);
        // Di dalam toggleStatus(User $user) setelah $user->update(...):
        ActivityLog::record(
            title: "Status akun {$user->name} diubah menjadi {$user->status}",
            description: "Diperbarui melalui User Administration",
            type: 'warning'
        );

        return redirect()
            ->route('user-administration')
            ->with('success', "Status akun {$user->name} berhasil diubah menjadi {$user->status}.");
    }
}