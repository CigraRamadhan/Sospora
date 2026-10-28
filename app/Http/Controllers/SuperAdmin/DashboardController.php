<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Sarana;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_sarana' => Sarana::count(),
            'sarana_aktif' => Sarana::where('status', 'aktif')->count(),
            'total_admin' => User::where('role', 'admin')->where('status', 'aktif')->count(),
            'total_user' => User::where('role', 'user')->count(),
            'total_booking' => Booking::count(),
            'booking_bulan_ini' => Booking::whereMonth('created_at', now()->month)->count(),
            'booking_menunggu' => Booking::where('status', 'menunggu_konfirmasi')->count(),
            'pendapatan_bulan_ini' => Pembayaran::whereHas('booking', fn ($q) => $q->whereMonth('created_at', now()->month))
                ->where('status_verifikasi', 'terverifikasi')
                ->sum('jumlah'),
        ];

        $bookingTerbaru = Booking::with(['sarana', 'user'])->latest()->limit(8)->get();

        return view('superadmin.dashboard', compact('stats', 'bookingTerbaru'));
    }
}
