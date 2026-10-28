<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    public function index(): View
    {
        $notifikasis = Auth::user()->notifikasi()
            ->with('booking.sarana')
            ->latest()
            ->paginate(20);

        // Mark all as read
        Auth::user()->notifikasi()->where('dibaca', false)->update(['dibaca' => true]);

        return view('user.notifikasi.index', compact('notifikasis'));
    }

    public function markRead(Notifikasi $notifikasi): RedirectResponse
    {
        abort_if($notifikasi->user_id !== Auth::id(), 403);
        $notifikasi->update(['dibaca' => true]);

        return back();
    }

    public function markAllRead(): RedirectResponse
    {
        Auth::user()->notifikasi()->where('dibaca', false)->update(['dibaca' => true]);

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }

    public function unreadCount(): JsonResponse
    {
        $count = Auth::user()->notifikasi()->where('dibaca', false)->count();

        return response()->json(['count' => $count]);
    }
}
