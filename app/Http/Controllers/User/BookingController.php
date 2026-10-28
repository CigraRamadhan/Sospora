<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Models\PengaturanSistem;
use App\Models\Sarana;
use App\Models\User;
use App\Services\KetersediaanSlotService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookingController extends Controller
{
    public function __construct(private readonly KetersediaanSlotService $slotService) {}

    public function index(): View
    {
        $bookings = Auth::user()->bookings()
            ->with(['sarana.fotos', 'sarana.kategori', 'pembayaran'])
            ->whereIn('status', ['menunggu_pembayaran', 'menunggu_konfirmasi', 'disetujui'])
            ->latest()
            ->paginate(10);

        return view('user.booking.index', compact('bookings'));
    }

    public function riwayat(): View
    {
        $bookings = Auth::user()->bookings()
            ->with(['sarana.fotos', 'sarana.kategori'])
            ->whereIn('status', ['selesai', 'dibatalkan', 'ditolak'])
            ->latest()
            ->paginate(10);

        return view('user.booking.riwayat', compact('bookings'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $request->validate([
            'sarana_id' => ['required', 'exists:sarana,id'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'slot_mulai' => ['required', 'string'],
        ]);

        $sarana = Sarana::with(['fotos', 'kategori'])->active()->findOrFail($request->sarana_id);
        $tanggal = $request->tanggal;
        $slotMulai = $request->slot_mulai;

        $slots = $this->slotService->getSlotsForDate($sarana, $tanggal);
        $selectedSlot = collect($slots)->firstWhere('slot_mulai', $slotMulai);

        if (! $selectedSlot || $selectedSlot['status'] !== 'tersedia') {
            return redirect()->route('sarana.show', $sarana->slug)
                ->withErrors(['slot' => 'Slot yang dipilih tidak tersedia. Silakan pilih slot lain.']);
        }

        $pengaturan = PengaturanSistem::first();

        return view('user.booking.create', compact('sarana', 'tanggal', 'slotMulai', 'selectedSlot', 'pengaturan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sarana_id' => ['required', 'exists:sarana,id'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'slot_mulai' => ['required', 'string'],
            'slot_selesai' => ['required', 'string'],
            'jumlah_peserta' => ['required', 'integer', 'min:1', 'max:100'],
            'catatan_user' => ['nullable', 'string', 'max:500'],
            'metode_bayar' => ['required', 'in:transfer,bayar_di_tempat'],
            'bukti_transfer' => ['required_if:metode_bayar,transfer', 'nullable', 'image', 'max:2048'],
        ]);

        $sarana = Sarana::active()->findOrFail($validated['sarana_id']);

        try {
            DB::transaction(function () use ($validated, $sarana, $request) {
                $isAvailable = $this->slotService->lockAndValidateSlot(
                    $sarana->id,
                    $validated['tanggal'],
                    $validated['slot_mulai']
                );

                if (! $isAvailable) {
                    throw new \Exception('Slot sudah terisi atau tidak valid.');
                }

                $slotActiveKey = sprintf('%d_%s_%s', $sarana->id, $validated['tanggal'], $validated['slot_mulai']);

                $statusAwal = $validated['metode_bayar'] === 'bayar_di_tempat'
                    ? 'menunggu_konfirmasi'
                    : 'menunggu_pembayaran';

                $booking = Booking::create([
                    'kode_booking' => Booking::generateKodeBooking(),
                    'user_id' => Auth::id(),
                    'sarana_id' => $sarana->id,
                    'tanggal' => $validated['tanggal'],
                    'slot_mulai' => $validated['slot_mulai'],
                    'slot_selesai' => $validated['slot_selesai'],
                    'jumlah_peserta' => $validated['jumlah_peserta'],
                    'catatan_user' => $validated['catatan_user'] ?? null,
                    'metode_bayar' => $validated['metode_bayar'],
                    'status' => $statusAwal,
                    'slot_active_key' => $slotActiveKey,
                ]);

                // Handle payment record
                $buktiPath = null;
                if ($request->hasFile('bukti_transfer')) {
                    $buktiPath = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
                }

                $totalHarga = $sarana->harga_per_slot * (int) $validated['jumlah_peserta'];

                Pembayaran::create([
                    'booking_id' => $booking->id,
                    'metode' => $validated['metode_bayar'] === 'transfer' ? 'transfer' : 'tunai',
                    'bukti_transfer' => $buktiPath,
                    'jumlah' => $totalHarga,
                    'status_verifikasi' => 'menunggu',
                ]);

                // Notify admins (via database notification for in-app)
                $this->notifikasiAdmins($booking, $sarana);
            });
        } catch (\Exception $e) {
            return back()->withErrors(['slot' => $e->getMessage()])->withInput();
        }

        return redirect()->route('user.booking.index')
            ->with('success', 'Booking berhasil dibuat! Silakan cek status booking Anda.');
    }

    public function show(string $kodeBooking): View
    {
        $booking = Booking::with(['sarana.fotos', 'sarana.kategori', 'pembayaran', 'verifikator'])
            ->where('kode_booking', $kodeBooking)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $pengaturan = PengaturanSistem::first();

        return view('user.booking.show', compact('booking', 'pengaturan'));
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_if(! $booking->canBeCancelledByUser(), 403, 'Booking ini tidak dapat dibatalkan.');

        DB::transaction(function () use ($booking) {
            $booking->update([
                'status' => 'dibatalkan',
                'slot_active_key' => null,
            ]);

            Notifikasi::create([
                'user_id' => $booking->user_id,
                'booking_id' => $booking->id,
                'tipe' => 'status_berubah',
                'pesan' => "Booking {$booking->kode_booking} untuk {$booking->sarana->nama_sarana} telah dibatalkan.",
            ]);
        });

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function uploadBukti(Request $request, Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_if($booking->status !== 'menunggu_pembayaran', 403, 'Status booking tidak sesuai.');

        $request->validate([
            'bukti_transfer' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');

        DB::transaction(function () use ($booking, $path) {
            $pembayaran = $booking->pembayaran;
            if ($pembayaran) {
                if ($pembayaran->bukti_transfer) {
                    Storage::disk('public')->delete($pembayaran->bukti_transfer);
                }
                $pembayaran->update([
                    'bukti_transfer' => $path,
                    'status_verifikasi' => 'menunggu',
                ]);
            } else {
                Pembayaran::create([
                    'booking_id' => $booking->id,
                    'metode' => 'transfer',
                    'bukti_transfer' => $path,
                    'jumlah' => $booking->total_harga,
                    'status_verifikasi' => 'menunggu',
                ]);
            }

            $booking->update(['status' => 'menunggu_konfirmasi']);
        });

        return back()->with('success', 'Bukti transfer berhasil diunggah. Menunggu konfirmasi admin.');
    }

    public function buktiTransfer(Booking $booking): BinaryFileResponse
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $pembayaran = $booking->pembayaran;
        abort_if(! $pembayaran || ! $pembayaran->bukti_transfer, 404, 'Bukti transfer tidak ditemukan.');

        $path = $pembayaran->bukti_transfer;
        abort_if(! Storage::disk('public')->exists($path), 404, 'File bukti transfer tidak ditemukan.');

        return response()->file(Storage::disk('public')->path($path));
    }

    private function notifikasiAdmins(Booking $booking, Sarana $sarana): void
    {
        $admins = User::whereIn('role', ['admin', 'superadmin'])
            ->where('status', 'aktif')
            ->get();

        foreach ($admins as $admin) {
            Notifikasi::create([
                'user_id' => $admin->id,
                'booking_id' => $booking->id,
                'tipe' => 'booking_baru',
                'pesan' => "Booking baru masuk: {$booking->kode_booking} untuk {$sarana->nama_sarana} oleh {$booking->user->nama}.",
            ]);
        }
    }
}
