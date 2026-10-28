@props(['status'])

@php
$classes = match ($status) {
    'menunggu_pembayaran' => 'bg-amber-100 text-amber-700 border-amber-200',
    'menunggu_konfirmasi' => 'bg-blue-100 text-blue-700 border-blue-200',
    'disetujui', 'aktif', 'tersedia' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
    'ditolak', 'tidak_tersedia', 'nonaktif' => 'bg-red-100 text-red-700 border-red-200',
    'selesai' => 'bg-slate-100 text-slate-600 border-slate-200',
    'dibatalkan' => 'bg-slate-100 text-slate-400 line-through border-slate-200',
    default => 'bg-slate-100 text-slate-600 border-slate-200',
};

$label = match ($status) {
    'menunggu_pembayaran' => 'Menunggu Pembayaran',
    'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
    'disetujui' => 'Disetujui',
    'ditolak' => 'Ditolak',
    'selesai' => 'Selesai',
    'dibatalkan' => 'Dibatalkan',
    'aktif' => 'Aktif',
    'nonaktif' => 'Nonaktif',
    'tersedia' => 'Tersedia',
    'tidak_tersedia' => 'Tidak Tersedia',
    default => ucfirst(str_replace('_', ' ', $status)),
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {$classes}"]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ str_contains($classes, 'line-through') ? 'bg-slate-400' : 'bg-current' }}"></span>
    {{ $label }}
</span>
