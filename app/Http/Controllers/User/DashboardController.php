<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $stats = [
            'aktif' => $user->bookings()->whereIn('status', ['menunggu_pembayaran', 'menunggu_konfirmasi', 'disetujui'])->count(),
            'total' => $user->bookings()->count(),
            'selesai' => $user->bookings()->where('status', 'selesai')->count(),
            'notifikasi_belum_dibaca' => $user->notifikasi()->where('dibaca', false)->count(),
        ];

        $bookingTerbaru = $user->bookings()
            ->with(['sarana.fotos', 'sarana.kategori'])
            ->latest()
            ->limit(5)
            ->get();

        return view('user.dashboard', compact('stats', 'bookingTerbaru'));
    }
}
