<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;

class DiscountController extends Controller
{
    public function index()
    {
        return Discount::all();
    }

    public function store(Request $request)
    {
        Discount::updateOrCreate(
            [
                'type' => $request->type,
                'min_nights' => $request->min_nights,
                'days_before_checkin' => $request->days_before_checkin
            ],
            [
                'value' => $request->value
            ]
        );

        return response()->json(['success' => true]);
    }
}