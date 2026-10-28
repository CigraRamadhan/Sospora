<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanSistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    public function edit(): View
    {
        $pengaturan = PengaturanSistem::firstOrCreate([], [
            'nama_bank' => '',
            'nomor_rekening' => '',
            'atas_nama' => '',
            'kontak_email' => '',
            'kontak_telepon' => '',
            'alamat_kantor' => '',
            'jam_layanan' => '',
        ]);

        return view('superadmin.pengaturan.edit', compact('pengaturan'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_bank' => ['required', 'string', 'max:100'],
            'nomor_rekening' => ['required', 'string', 'max:50'],
            'atas_nama' => ['required', 'string', 'max:150'],
            'kontak_email' => ['required', 'email', 'max:100'],
            'kontak_telepon' => ['required', 'string', 'max:30'],
            'alamat_kantor' => ['required', 'string'],
            'jam_layanan' => ['required', 'string', 'max:150'],
        ]);

        PengaturanSistem::updateOrCreate([], $validated);

        return redirect()->route('superadmin.pengaturan.edit')->with('success', 'Pengaturan sistem berhasil disimpan.');
    }
}
