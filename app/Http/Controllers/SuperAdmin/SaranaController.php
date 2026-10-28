<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSarana;
use App\Models\Sarana;
use App\Models\SaranaFoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SaranaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Sarana::with(['kategori'])->withTrashed()->latest();

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('status')) {
            if ($request->status === 'dihapus') {
                $query->onlyTrashed();
            } else {
                $query->withoutTrashed()->where('status', $request->status);
            }
        }

        $sarana = $query->paginate(15)->withQueryString();
        $kategoris = KategoriSarana::orderBy('nama_kategori')->get();

        return view('superadmin.sarana.index', compact('sarana', 'kategoris'));
    }

    public function create(): View
    {
        $kategoris = KategoriSarana::orderBy('nama_kategori')->get();

        return view('superadmin.sarana.create', compact('kategoris'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSarana($request);

        DB::transaction(function () use ($validated, $request) {
            $sarana = Sarana::create([
                'kategori_id' => $validated['kategori_id'],
                'nama_sarana' => $validated['nama_sarana'],
                'slug' => Str::slug($validated['nama_sarana']),
                'deskripsi' => $validated['deskripsi'],
                'lokasi' => $validated['lokasi'],
                'harga_per_slot' => $validated['harga_per_slot'],
                'jam_buka' => $validated['jam_buka'],
                'jam_tutup' => $validated['jam_tutup'],
                'interval_slot_menit' => $validated['interval_slot_menit'],
                'status' => $validated['status'],
            ]);

            $this->handleFotoUpload($request, $sarana);
        });

        return redirect()->route('superadmin.sarana.index')->with('success', 'Sarana berhasil ditambahkan.');
    }

    public function edit(Sarana $sarana): View
    {
        $kategoris = KategoriSarana::orderBy('nama_kategori')->get();
        $sarana->load('fotos');

        return view('superadmin.sarana.edit', compact('sarana', 'kategoris'));
    }

    public function update(Request $request, Sarana $sarana): RedirectResponse
    {
        $validated = $this->validateSarana($request);

        DB::transaction(function () use ($validated, $request, $sarana) {
            $slug = $sarana->nama_sarana !== $validated['nama_sarana']
                ? Str::slug($validated['nama_sarana'])
                : $sarana->slug;

            $sarana->update([
                'kategori_id' => $validated['kategori_id'],
                'nama_sarana' => $validated['nama_sarana'],
                'slug' => $slug,
                'deskripsi' => $validated['deskripsi'],
                'lokasi' => $validated['lokasi'],
                'harga_per_slot' => $validated['harga_per_slot'],
                'jam_buka' => $validated['jam_buka'],
                'jam_tutup' => $validated['jam_tutup'],
                'interval_slot_menit' => $validated['interval_slot_menit'],
                'status' => $validated['status'],
            ]);

            $this->handleFotoUpload($request, $sarana);
        });

        return redirect()->route('superadmin.sarana.index')->with('success', 'Sarana berhasil diperbarui.');
    }

    public function toggleStatus(Sarana $sarana): RedirectResponse
    {
        $newStatus = $sarana->status === 'aktif' ? 'nonaktif' : 'aktif';
        $sarana->update(['status' => $newStatus]);

        $label = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Sarana {$sarana->nama_sarana} berhasil {$label}.");
    }

    public function destroy(Sarana $sarana): RedirectResponse
    {
        // Check if sarana has active bookings
        $hasActiveBookings = $sarana->bookings()
            ->whereNotIn('status', ['selesai', 'dibatalkan', 'ditolak'])
            ->exists();

        if ($hasActiveBookings) {
            return back()->withErrors(['error' => 'Sarana ini memiliki booking aktif dan tidak dapat dihapus. Nonaktifkan terlebih dahulu.']);
        }

        $sarana->delete(); // Soft delete

        return redirect()->route('superadmin.sarana.index')->with('success', 'Sarana berhasil dihapus.');
    }

    public function deleteFoto(Sarana $sarana, SaranaFoto $foto): RedirectResponse
    {
        abort_if($foto->sarana_id !== $sarana->id, 404);

        Storage::disk('public')->delete($foto->path_file);
        $foto->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    private function validateSarana(Request $request): array
    {
        return $request->validate([
            'kategori_id' => ['required', 'exists:kategori_sarana,id'],
            'nama_sarana' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'lokasi' => ['required', 'string', 'max:255'],
            'harga_per_slot' => ['required', 'numeric', 'min:0'],
            'jam_buka' => ['required', 'date_format:H:i'],
            'jam_tutup' => ['required', 'date_format:H:i', 'after:jam_buka'],
            'interval_slot_menit' => ['required', 'integer', 'in:30,60,90,120'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'fotos.*' => ['nullable', 'image', 'max:3072'],
        ]);
    }

    private function handleFotoUpload(Request $request, Sarana $sarana): void
    {
        if (! $request->hasFile('fotos')) {
            return;
        }

        $lastUrutan = $sarana->fotos()->max('urutan') ?? 0;

        foreach ($request->file('fotos') as $index => $file) {
            $path = $file->store("sarana/{$sarana->id}", 'public');
            SaranaFoto::create([
                'sarana_id' => $sarana->id,
                'path_file' => $path,
                'urutan' => $lastUrutan + $index + 1,
            ]);
        }
    }
}
