<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
/*
وظيفته:

جلب كل الخدمات
البحث
فلترة حسب category
عرض تفاصيل الخدمة

*/ 
class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with(['provider.user', 'category', 'images'])
            ->where('status', 'active');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $services = $query->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json([
            'icon' => 'success',
            'title' => 'Services loaded',
            'data' => $services->items(),
            'pagination' => [
                'current_page' => $services->currentPage(),
                'last_page' => $services->lastPage(),
                'per_page' => $services->perPage(),
                'total' => $services->total(),
            ],
        ], 200);
    }

    public function show($id)
    {
        $service = Service::with(['provider.user', 'category', 'images', 'reviews.user'])
            ->where('status', 'active')
            ->findOrFail($id);

        return response()->json([
            'icon' => 'success',
            'title' => 'Service loaded',
            'data' => [
                'id' => $service->id,
                'title' => $service->title,
                'description' => $service->description,
                'price' => (float) $service->price,
                'currency' => $service->currency,
                'rating_avg' => (float) $service->rating_avg,
                'reviews_count' => (int) $service->reviews_count,
                'image' => $service->image,
                'is_featured' => (bool) $service->is_featured,
                'category' => $service->category ? [
                    'id' => $service->category->id,
                    'name' => $service->category->name,
                ] : null,
                'provider' => $service->provider ? [
                    'id' => $service->provider->id,
                    'business_name' => $service->provider->business_name,
                    'rating' => (float) $service->provider->rating,
                    'cover_image' => $service->provider->cover_image,
                    'phone' => $service->provider->phone,
                ] : null,
                'images' => $service->images->map(fn ($image) => [
                    'id' => $image->id,
                    'image_path' => $image->image_path,
                    'is_cover' => (bool) $image->is_cover,
                ])->values(),
                'reviews' => $service->reviews->map(fn ($review) => [
                    'id' => $review->id,
                    'rating' => (int) $review->rating,
                    'comment' => $review->comment,
                    'user' => $review->user ? [
                        'id' => $review->user->id,
                        'name' => $review->user->name,
                    ] : null,
                ])->values(),
            ],
        ], 200);
    }
}
