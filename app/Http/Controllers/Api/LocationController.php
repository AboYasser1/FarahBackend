<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $locations = $request->user()->locations()->with('city')->get();

        return response()->json([
            'icon' => 'success',
            'title' => 'Locations retrieved successfully.',
            'data' => $locations,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city_id' => 'nullable|exists:cities,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon' => 'error',
                'title' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $location = $request->user()->locations()->create($validator->validated());

        return response()->json([
            'icon' => 'success',
            'title' => 'Location created successfully.',
            'data' => $location,
        ], 201);
    }

    public function show(Request $request, Location $location)
    {
        $this->authorizeLocation($request, $location);

        return response()->json([
            'icon' => 'success',
            'title' => 'Location retrieved successfully.',
            'data' => $location->load('city'),
        ], 200);
    }

    public function update(Request $request, Location $location)
    {
        $this->authorizeLocation($request, $location);

        $validator = Validator::make($request->all(), [
            'label' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city_id' => 'nullable|exists:cities,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon' => 'error',
                'title' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $location->update($validator->validated());

        return response()->json([
            'icon' => 'success',
            'title' => 'Location updated successfully.',
            'data' => $location,
        ], 200);
    }

    public function destroy(Request $request, Location $location)
    {
        $this->authorizeLocation($request, $location);

        $location->delete();

        return response()->json([
            'icon' => 'success',
            'title' => 'Location deleted successfully.',
        ], 200);
    }

    protected function authorizeLocation(Request $request, Location $location): void
    {
        if ($request->user()->id !== $location->user_id) {
            abort(403, 'This location does not belong to the current user.');
        }
    }
}
