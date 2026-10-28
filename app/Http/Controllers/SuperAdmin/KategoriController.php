<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSarana;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriController extends Controller
{
    public function index(): View
    {
        $kategoris = KategoriSarana::withCount(['saranas', 'saranas as sarana_aktif_count' => fn ($q) => $q->where('status', 'aktif')])->get();

        return view('superadmin.kategori.index', compact('kategoris'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori_sarana,nama_kategori'],
            'icon' => ['nullable', 'string', 'max:10'],
        ]);

        KategoriSarana::create($validated);

        return redirect()->route('superadmin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriSarana $kategori): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori_sarana,nama_kategori,'.$kategori->id],
            'icon' => ['nullable', 'string', 'max:10'],
        ]);

        $kategori->update($validated);

        return redirect()->route('superadmin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriSarana $kategori): RedirectResponse
    {
        if ($kategori->saranas()->withoutTrashed()->exists()) {
            return back()->withErrors(['error' => 'Kategori ini masih memiliki sarana aktif dan tidak dapat dihapus.']);
        }

        $kategori->delete();

        return redirect()->route('superadmin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
