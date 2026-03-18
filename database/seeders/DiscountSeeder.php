<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            Discount::insert([
            ['type' => 'long_stay', 'min_nights' => 3, 'value' => 5],
            ['type' => 'long_stay', 'min_nights' => 6, 'value' => 20],
            ['type' => 'last_minute', 'days_before_checkin' => 3, 'value' => 5],
        ]);
    }
}
