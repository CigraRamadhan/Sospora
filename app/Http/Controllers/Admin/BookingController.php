<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Models\Sarana;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['sarana', 'user', 'pembayaran', 'verifikator'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sarana_id')) {
            $query->where('sarana_id', $request->sarana_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_booking', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('nama', 'like', "%{$search}%"));
            });
        }

        $bookings = $query->paginate(15)->withQueryString();
        $saranas = Sarana::orderBy('nama_sarana')->get(['id', 'nama_sarana']);

        return view('admin.booking.index', compact('bookings', 'saranas'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['sarana.fotos', 'user', 'pembayaran', 'verifikator']);

        return view('admin.booking.show', compact('booking'));
    }

    public function setujui(Request $request, Booking $booking): RedirectResponse
    {
        abort_if(! in_array($booking->status, ['menunggu_konfirmasi', 'menunggu_pembayaran'], true), 422, 'Status booking tidak dapat disetujui.');

        $validated = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($booking, $validated) {
            $booking->update([
                'status' => 'disetujui',
                'catatan_admin' => $validated['catatan_admin'] ?? null,
                'diverifikasi_oleh' => Auth::id(),
                'diverifikasi_pada' => now(),
            ]);

            if ($booking->pembayaran) {
                $booking->pembayaran->update([
                    'status_verifikasi' => 'terverifikasi',
                    'diverifikasi_oleh' => Auth::id(),
                    'diverifikasi_pada' => now(),
                ]);
            }

            Notifikasi::create([
                'user_id' => $booking->user_id,
                'booking_id' => $booking->id,
                'tipe' => 'status_berubah',
                'pesan' => "Booking {$booking->kode_booking} Anda untuk {$booking->sarana->nama_sarana} telah DISETUJUI! Silakan hadir sesuai jadwal yang telah dipesan.",
            ]);
        });

        return back()->with('success', "Booking {$booking->kode_booking} berhasil disetujui.");
    }

    public function tolak(Request $request, Booking $booking): RedirectResponse
    {
        abort_if(! in_array($booking->status, ['menunggu_konfirmasi', 'menunggu_pembayaran', 'disetujui'], true), 422, 'Status booking tidak dapat ditolak.');

        $validated = $request->validate([
            'catatan_admin' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($booking, $validated) {
            $booking->update([
                'status' => 'ditolak',
                'catatan_admin' => $validated['catatan_admin'],
                'diverifikasi_oleh' => Auth::id(),
                'diverifikasi_pada' => now(),
                'slot_active_key' => null,
            ]);

            if ($booking->pembayaran) {
                $booking->pembayaran->update([
                    'status_verifikasi' => 'ditolak',
                    'diverifikasi_oleh' => Auth::id(),
                    'diverifikasi_pada' => now(),
                ]);
            }

            Notifikasi::create([
                'user_id' => $booking->user_id,
                'booking_id' => $booking->id,
                'tipe' => 'status_berubah',
                'pesan' => "Booking {$booking->kode_booking} Anda untuk {$booking->sarana->nama_sarana} ditolak. Alasan: {$validated['catatan_admin']}",
            ]);
        });

        return back()->with('success', "Booking {$booking->kode_booking} telah ditolak.");
    }

    public function konfirmasiPembayaran(Request $request, Booking $booking): RedirectResponse
    {
        abort_if($booking->metode_bayar !== 'bayar_di_tempat', 422, 'Hanya untuk metode bayar di tempat.');
        abort_if($booking->status !== 'menunggu_konfirmasi', 422, 'Status booking tidak sesuai.');

        DB::transaction(function () use ($booking) {
            $booking->update([
                'status' => 'disetujui',
                'diverifikasi_oleh' => Auth::id(),
                'diverifikasi_pada' => now(),
            ]);

            if ($booking->pembayaran) {
                $booking->pembayaran->update([
                    'status_verifikasi' => 'terverifikasi',
                    'diverifikasi_oleh' => Auth::id(),
                    'diverifikasi_pada' => now(),
                ]);
            } else {
                Pembayaran::create([
                    'booking_id' => $booking->id,
                    'metode' => 'tunai',
                    'jumlah' => $booking->total_harga,
                    'status_verifikasi' => 'terverifikasi',
                    'diverifikasi_oleh' => Auth::id(),
                    'diverifikasi_pada' => now(),
                ]);
            }

            Notifikasi::create([
                'user_id' => $booking->user_id,
                'booking_id' => $booking->id,
                'tipe' => 'status_berubah',
                'pesan' => "Pembayaran booking {$booking->kode_booking} untuk {$booking->sarana->nama_sarana} telah dikonfirmasi. Booking Anda DISETUJUI!",
            ]);
        });

        return back()->with('success', "Pembayaran booking {$booking->kode_booking} dikonfirmasi. Booking disetujui.");
    }

    public function selesai(Booking $booking): RedirectResponse
    {
        abort_if($booking->status !== 'disetujui', 422, 'Hanya booking yang disetujui dapat ditandai selesai.');

        DB::transaction(function () use ($booking) {
            $booking->update([
                'status' => 'selesai',
                'slot_active_key' => null,
            ]);

            Notifikasi::create([
                'user_id' => $booking->user_id,
                'booking_id' => $booking->id,
                'tipe' => 'status_berubah',
                'pesan' => "Booking {$booking->kode_booking} untuk {$booking->sarana->nama_sarana} telah selesai. Terima kasih sudah menggunakan sarana Dispora Kota Bandung!",
            ]);
        });

        return back()->with('success', "Booking {$booking->kode_booking} ditandai selesai.");
    }

    public function buktiTransfer(Booking $booking): BinaryFileResponse
    {
        $pembayaran = $booking->pembayaran;
        abort_if(! $pembayaran || ! $pembayaran->bukti_transfer, 404, 'Bukti transfer tidak ditemukan.');

        $path = $pembayaran->bukti_transfer;
        abort_if(! Storage::disk('public')->exists($path), 404, 'File bukti transfer tidak ditemukan di penyimpanan server.');

        return response()->file(Storage::disk('public')->path($path));
    }
}
