<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()->withInput($request->only('email', 'remember'))->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if (! $user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda berstatus nonaktif. Silakan hubungi pengelola Dispora Kota Bandung.',
            ]);
        }

        // Redirect based on role
        if ($user->isSuperAdmin()) {
            return redirect()->intended(route('superadmin.dashboard'))->with('success', 'Selamat datang kembali, '.$user->nama);
        }

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, '.$user->nama);
        }

        return redirect()->intended(route('user.dashboard'))->with('success', 'Selamat datang di SOSPORA Kota Bandung!');
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'nama' => $validated['nama'],
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'status' => 'aktif',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // Create welcome notification
        Notifikasi::create([
            'user_id' => $user->id,
            'tipe' => 'status_berubah',
            'pesan' => 'Selamat datang di SOSPORA! Akun warga Anda berhasil terdaftar. Silakan jelajahi sarana olahraga Kota Bandung.',
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Pendaftaran akun berhasil! Selamat datang di SOSPORA.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }
}
