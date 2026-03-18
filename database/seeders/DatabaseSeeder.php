<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;
use App\Models\Inventory;
use App\Models\Discount;
use App\Models\MealPlan;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🔥 Room Types
        $standard = RoomType::create([
            'name' => 'Standard',
            'capacity' => 3,
            'total_rooms' => 5
        ]);

        $deluxe = RoomType::create([
            'name' => 'Deluxe',
            'capacity' => 3,
            'total_rooms' => 5
        ]);

        // 🔥 Inventory (30 days)
        $start = Carbon::today();

        foreach (range(0, 29) as $i) {
            $date = $start->copy()->addDays($i);

            Inventory::create([
                'room_type_id' => $standard->id,
                'date' => $date,
                'available_rooms' => 5,
                'base_price' => 2000
            ]);

            Inventory::create([
                'room_type_id' => $deluxe->id,
                'date' => $date,
                'available_rooms' => 5,
                'base_price' => 3500
            ]);
        }

        // 🔥 DISCOUNTS (IMPORTANT FIX)
        Discount::insert([

            // Long Stay Discounts
            [
                'type' => 'long_stay',
                'value' => 5,
                'min_nights' => 3,
                'days_before_checkin' => null
            ],
            [
                'type' => 'long_stay',
                'value' => 20,
                'min_nights' => 6,
                'days_before_checkin' => null
            ],

            // Last Minute Discount
            [
                'type' => 'last_minute',
                'value' => 5,
                'min_nights' => null,
                'days_before_checkin' => 3
            ]
        ]);

        // 🔥 Meal Plans
        MealPlan::insert([
            ['name' => 'room_only', 'price_modifier' => 0],
            ['name' => 'breakfast', 'price_modifier' => 500]
        ]);
    }
}