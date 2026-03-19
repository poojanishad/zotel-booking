<?php

namespace App\Services;

use App\Models\RoomType;

class SearchService
{
    public function search($data)
    {
        $results = [];

        foreach (RoomType::all() as $room) {

            //  Always 1 room for 1–3 adults
            $roomsNeeded = 1;

            $availability = app(AvailabilityService::class)
                ->check($room->id, $data['check_in'], $data['check_out']);

            // only check at least 1 room available
            if ($availability < 1) {
                $availability = 0;
            }

            $pricing = app(PricingService::class)
                ->calculate(
                    $room->id,
                    $data['check_in'],
                    $data['check_out'],
                    $data['meal_plan'] ?? null,
                    $data['guests'] 
                );

            $results[] = [
                'room_type' => $room->name,
                'capacity' => $room->capacity,
                'rooms_needed' => $roomsNeeded,
                'available_rooms' => $availability,
                'sold_out' => $availability == 0,
                'pricing' => $pricing
            ];
        }

        return $results;
    }
}