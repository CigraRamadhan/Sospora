<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $bulan = $request->integer('bulan', now()->month);
        $tahun = $request->integer('tahun', now()->year);

        $bookings = Booking::with(['sarana.kategori', 'user', 'pembayaran'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->latest('tanggal')
            ->get();

        $statsByStatus = $bookings->groupBy('status')->map->count();

        $statsBySarana = Booking::with('sarana')
            ->selectRaw('sarana_id, count(*) as total, sum(case when status = "disetujui" or status = "selesai" then 1 else 0 end) as disetujui')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('sarana_id')
            ->get();

        $totalPendapatan = Pembayaran::whereHas('booking', fn ($q) => $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun))
            ->where('status_verifikasi', 'terverifikasi')
            ->sum('jumlah');

        $availableYears = collect(range(now()->year - 2, now()->year + 1));

        return view('superadmin.laporan.index', compact(
            'bookings',
            'statsByStatus',
            'statsBySarana',
            'totalPendapatan',
            'bulan',
            'tahun',
            'availableYears'
        ));
    }
}
