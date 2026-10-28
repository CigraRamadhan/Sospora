<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Sarana;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'menunggu_verifikasi' => Booking::where('status', 'menunggu_konfirmasi')->count(),
            'disetujui_hari_ini' => Booking::where('status', 'disetujui')->whereDate('diverifikasi_pada', today())->count(),
            'total_sarana_aktif' => Sarana::where('status', 'aktif')->count(),
            'booking_hari_ini' => Booking::whereDate('created_at', today())->count(),
        ];

        $bookingMenunggu = Booking::with(['sarana', 'user', 'pembayaran'])
            ->where('status', 'menunggu_konfirmasi')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'bookingMenunggu'));
    }
}
