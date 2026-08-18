<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with(['category', 'provider.user', 'images', 'reviews.user'])
            ->where('status', 'active');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $services = $query->latest()->paginate(12);

        return response()->json([
            'data' => $services,
        ]);
    }

    public function show($id)
    {
        $service = Service::with(['category', 'provider.user', 'images', 'reviews.user'])
            ->where('status', 'active')
            ->findOrFail($id);

        return response()->json([
            'data' => $service,
        ]);
    }
}
