<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $roomTypeId = $request->room_type_id ?? 1;

        $data = Inventory::where('room_type_id', $roomTypeId)
            ->orderBy('date')
            ->get()
            ->map(function ($item) {

                return [
                    'date' => $item->date,
                    'available' => $item->available_rooms,
                    'price_1' => $item->base_price,
                    'price_2' => $item->base_price + 500,
                    'price_3' => $item->base_price + 1000,
                    'breakfast' => 400
                ];
            });

        return response()->json($data);
    }
}