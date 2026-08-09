<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    /**
     * Return list of cities for API (ordered by name).
     */
    public function index(Request $request)
    {
        $cities = City::orderBy('name')->get();

        return response()->json([
            'icon' => 'success',
            'title' => 'Cities fetched',
            'data' => $cities,
        ], 200);
    }
}
