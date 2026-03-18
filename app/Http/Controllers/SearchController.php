<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SearchService;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $data = $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:3',
            'meal_plan' => 'nullable|in:room_only,breakfast'
        ]);

        return response()->json(
            app(SearchService::class)->search($data)
        );
    }
}