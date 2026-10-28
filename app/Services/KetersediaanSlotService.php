<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Sarana;
use Carbon\Carbon;

class KetersediaanSlotService
{
    /**
     * Generate all time slots for a given facility on a specific date,
     * including availability status (tersedia, terisi, tidak_tersedia).
     *
     * @return array<int, array{
     *     slot_mulai: string,
     *     slot_selesai: string,
     *     label: string,
     *     status: 'tersedia'|'terisi'|'tidak_tersedia',
     *     keterangan: string
     * }>
     */
    public function getSlotsForDate(Sarana $sarana, string $tanggal): array
    {
        $selectedDate = Carbon::parse($tanggal)->startOfDay();
        $isToday = $selectedDate->isToday();
        $isPast = $selectedDate->isPast() && ! $isToday;
        $nowTime = Carbon::now()->format('H:i');

        if ($sarana->status !== 'aktif' || $isPast) {
            return [];
        }

        // Fetch all active bookings on this date for the facility
        $bookedSlots = Booking::where('sarana_id', $sarana->id)
            ->where('tanggal', $tanggal)
            ->whereNotIn('status', ['dibatalkan', 'ditolak'])
            ->get(['slot_mulai', 'slot_selesai'])
            ->mapWithKeys(function ($booking) {
                $start = substr($booking->slot_mulai, 0, 5);

                return [$start => true];
            })
            ->all();

        $slots = [];
        $startTime = Carbon::createFromTimeString($sarana->jam_buka);
        $endTime = Carbon::createFromTimeString($sarana->jam_tutup);
        $intervalMinutes = max(15, (int) $sarana->interval_slot_menit);

        $cursor = $startTime->copy();

        while ($cursor->copy()->addMinutes($intervalMinutes)->lte($endTime)) {
            $slotStart = $cursor->format('H:i');
            $slotEnd = $cursor->copy()->addMinutes($intervalMinutes)->format('H:i');

            $status = 'tersedia';
            $keterangan = 'Slot tersedia untuk dibooking';

            if (isset($bookedSlots[$slotStart])) {
                $status = 'terisi';
                $keterangan = 'Slot sudah dipesan';
            } elseif ($isToday && $slotStart <= $nowTime) {
                $status = 'tidak_tersedia';
                $keterangan = 'Waktu telah lewat';
            }

            $slots[] = [
                'slot_mulai' => $slotStart,
                'slot_selesai' => $slotEnd,
                'label' => sprintf('%s - %s WIB', $slotStart, $slotEnd),
                'status' => $status,
                'keterangan' => $keterangan,
            ];

            $cursor->addMinutes($intervalMinutes);
        }

        return $slots;
    }

    /**
     * Atomically check if slot is available with row lock within a DB transaction (BOK-05).
     * Returns true if available, false if already taken or invalid.
     */
    public function lockAndValidateSlot(int $saranaId, string $tanggal, string $slotMulai): bool
    {
        $sarana = Sarana::find($saranaId);
        if (! $sarana || $sarana->status !== 'aktif') {
            return false;
        }

        // Format slotMulai to H:i:s or H:i
        $formattedSlotMulai = strlen($slotMulai) === 5 ? $slotMulai.':00' : $slotMulai;

        // Atomic check with row locking
        $existingBooking = Booking::where('sarana_id', $saranaId)
            ->where('tanggal', $tanggal)
            ->where(function ($query) use ($slotMulai, $formattedSlotMulai) {
                $query->where('slot_mulai', $slotMulai)
                    ->orWhere('slot_mulai', $formattedSlotMulai)
                    ->orWhereRaw('substr(slot_mulai, 1, 5) = ?', [substr($slotMulai, 0, 5)]);
            })
            ->whereNotIn('status', ['dibatalkan', 'ditolak'])
            ->lockForUpdate()
            ->first();

        if ($existingBooking) {
            return false;
        }

        // Validate that date is not in past
        $selectedDate = Carbon::parse($tanggal)->startOfDay();
        if ($selectedDate->isPast() && ! $selectedDate->isToday()) {
            return false;
        }

        // If today, slot must be in the future
        if ($selectedDate->isToday()) {
            $nowTime = Carbon::now()->format('H:i');
            if (substr($slotMulai, 0, 5) <= $nowTime) {
                return false;
            }
        }

        return true;
    }
}
