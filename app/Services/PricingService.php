<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\MealPlan;
use App\Models\Discount;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PricingService
{
    public function calculate($roomTypeId, $checkIn, $checkOut, $mealPlan = null, $guests = 1)
    {
        $dates = CarbonPeriod::create($checkIn, $checkOut);
        $total = 0;

        $meal = 0;
        if ($mealPlan) {
            $meal = MealPlan::where('name', $mealPlan)->value('price_modifier') ?? 0;
        }

        foreach ($dates as $date) {
            $inventory = Inventory::where('room_type_id', $roomTypeId)
                ->whereDate('date', $date)
                ->first();

            if (!$inventory) continue;

            // 👇 per person pricing (1–3)
            if ($guests == 1) {
                $price = $inventory->base_price;
            } elseif ($guests == 2) {
                $price = $inventory->base_price + 500;
            } else {
                $price = $inventory->base_price + 1000;
            }

            $total += ($price + $meal);
        }

        // 🔥 DISCOUNT LOGIC
        $discountPercent = 0;

        $nights = Carbon::parse($checkIn)->diffInDays($checkOut);

        // ✅ Long stay
        $longStay = Discount::where('type', 'long_stay')
            ->where('min_nights', '<=', $nights)
            ->orderByDesc('min_nights')
            ->first();

        if ($longStay) {
            $discountPercent = max($discountPercent, $longStay->value);
        }

        // ✅ Last minute
        $daysBefore = Carbon::now()->diffInDays(Carbon::parse($checkIn), false);

        $lastMinute = Discount::where('type', 'last_minute')
            ->where('days_before_checkin', '>=', $daysBefore)
            ->orderBy('days_before_checkin')
            ->first();

        if ($lastMinute) {
            $discountPercent = max($discountPercent, $lastMinute->value);
        }

        // 💰 Apply discount
        $discountAmount = ($total * $discountPercent) / 100;
        $final = $total - $discountAmount;

        return [
            'total' => $total,
            'discount' => $discountAmount,
            'final' => $final,
            'discount_percent' => $discountPercent
        ];
    }
}