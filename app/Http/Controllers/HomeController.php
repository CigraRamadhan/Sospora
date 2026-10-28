<?php

namespace App\Http\Controllers;

use App\Models\KategoriSarana;
use App\Models\PengaturanSistem;
use App\Models\Sarana;
use App\Services\KetersediaanSlotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private readonly KetersediaanSlotService $slotService) {}

    public function index(): View
    {
        $kategoris = KategoriSarana::withCount(['saranas' => fn ($q) => $q->where('status', 'aktif')])->get();
        $saranaPopuler = Sarana::with(['kategori', 'fotos'])
            ->active()
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit(6)
            ->get();
        $pengaturan = PengaturanSistem::first();

        return view('home.index', compact('kategoris', 'saranaPopuler', 'pengaturan'));
    }

    public function katalog(Request $request): View
    {
        $query = Sarana::with(['kategori', 'fotos'])->active();

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_sarana', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('harga_max')) {
            $query->where('harga_per_slot', '<=', (int) $request->harga_max);
        }

        $sarana = $query->orderBy('nama_sarana')->paginate(9)->withQueryString();
        $kategoris = KategoriSarana::orderBy('nama_kategori')->get();

        return view('home.katalog', compact('sarana', 'kategoris'));
    }

    public function detail(string $slug): View
    {
        $sarana = Sarana::with(['kategori', 'fotos'])->active()->where('slug', $slug)->firstOrFail();
        $tanggal = request('tanggal', now()->format('Y-m-d'));
        $slots = $this->slotService->getSlotsForDate($sarana, $tanggal);

        return view('home.detail', compact('sarana', 'slots', 'tanggal'));
    }

    public function ketersediaan(Sarana $sarana, Request $request): JsonResponse
    {
        $tanggal = $request->validate([
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
        ])['tanggal'];

        $slots = $this->slotService->getSlotsForDate($sarana, $tanggal);

        return response()->json(['slots' => $slots]);
    }
}
