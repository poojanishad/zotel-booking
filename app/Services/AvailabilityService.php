<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Booking;
use Carbon\CarbonPeriod;

class AvailabilityService
{
    public function check($roomTypeId, $checkIn, $checkOut)
    {
        $dates = CarbonPeriod::create($checkIn, $checkOut);

        $minAvailable = PHP_INT_MAX;

        foreach ($dates as $date) {
            $inventory = Inventory::where('room_type_id', $roomTypeId)
                ->whereDate('date', $date)
                ->first();

            if (!$inventory) return 0;

            $booked = Booking::where('room_type_id', $roomTypeId)
                ->where('check_in', '<=', $date)
                ->where('check_out', '>', $date)
                ->sum('rooms_booked');

            $available = $inventory->available_rooms - $booked;

            $minAvailable = min($minAvailable, $available);
        }

        return max(0, $minAvailable);
    }
}