<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $admins = User::where('role', 'admin')
            ->withCount('verifiedBookings')
            ->latest()
            ->paginate(15);

        return view('superadmin.admin.index', compact('admins'));
    }

    public function create(): View
    {
        return view('superadmin.admin.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $admin = User::create([
            'nama' => $validated['nama'],
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $admin->assignRole('admin');

        return redirect()->route('superadmin.admin.index')->with('success', "Akun admin {$admin->nama} berhasil dibuat.");
    }

    public function edit(User $admin): View
    {
        abort_if($admin->role !== 'admin', 404);

        return view('superadmin.admin.edit', compact('admin'));
    }

    public function update(Request $request, User $admin): RedirectResponse
    {
        abort_if($admin->role !== 'admin', 404);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email,'.$admin->id],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $updateData = [
            'nama' => $validated['nama'],
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $admin->update($updateData);

        return redirect()->route('superadmin.admin.index')->with('success', "Akun admin {$admin->nama} berhasil diperbarui.");
    }

    public function toggleStatus(User $admin): RedirectResponse
    {
        abort_if($admin->role !== 'admin', 404);

        $newStatus = $admin->status === 'aktif' ? 'nonaktif' : 'aktif';
        $admin->update(['status' => $newStatus]);

        $label = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun admin {$admin->nama} berhasil {$label}.");
    }
}
