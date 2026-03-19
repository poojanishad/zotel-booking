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
                    'id' => $item->id,
                    'date' => $item->date,
                    'available' => (int)$item->available_rooms,
                    'price_1' => (int)$item->base_price,
                    'price_2' => (int)$item->base_price + 500,
                    'price_3' => (int)$item->base_price + 1000,
                    'breakfast' => (int)($item->breakfast_price ?? 400)
                ];
            });

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $data = $request->json()->all();

        $id = $data['id'] ?? null;
        $field = $data['field'] ?? null;
        $value = $data['value'] ?? null;

        if (!$id || !$field || $value === null) {
            return response()->json(['success' => false, 'message' => 'Invalid data'], 400);
        }

        $inventory = Inventory::find($id);

        if (!$inventory) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $value = (int)$value;

        try {

            if ($field === 'available') {
                $inventory->available_rooms = $value;
            }

            if ($field === 'price_1') {
                $inventory->base_price = $value;
            }

            if ($field === 'price_2') {
                $inventory->base_price = $value - 500;
            }

            if ($field === 'price_3') {
                $inventory->base_price = $value - 1000;
            }

            if ($field === 'breakfast') {
                $inventory->breakfast_price = $value;
            }

            $inventory->save();

            return response()->json([
                'success' => true,
                'saved_value' => $value
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}