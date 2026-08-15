<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProviderProfile;
use App\Models\Service;
use Illuminate\Http\Request;
/*
تجميع البيانات الرئيسية للصفحة الرئيسية
حسب صورة تصميم المصممة uiux
*/
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $featuredProviders = ProviderProfile::with(['user'])
            ->where('is_featured', true)
            ->limit(4)
            ->get()
            ->map(function ($provider) {
                return [
                    'id' => $provider->id,
                    'business_name' => $provider->business_name,
                    'rating' => (float) $provider->rating,
                    'cover_image' => $provider->cover_image,
                    'user' => $provider->user ? [
                        'id' => $provider->user->id,
                        'name' => $provider->user->name,
                        'avatar' => $provider->user->avatar,
                    ] : null,
                ];
            });

        $categories = Category::where('status', 'active')
            ->latest()
            ->limit(8)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'image' => $category->image,
                ];
            });

        $services = Service::with(['provider.user', 'category', 'images'])
            ->where('status', 'active')
            ->orderByDesc('is_featured')
            ->limit(8)
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'title' => $service->title,
                    'description' => $service->description,
                    'price' => (float) $service->price,
                    'currency' => $service->currency,
                    'rating_avg' => (float) $service->rating_avg,
                    'reviews_count' => (int) $service->reviews_count,
                    'image' => $service->image,
                    'provider' => $service->provider ? [
                        'id' => $service->provider->id,
                        'business_name' => $service->provider->business_name,
                    ] : null,
                    'category' => $service->category ? [
                        'id' => $service->category->id,
                        'name' => $service->category->name,
                    ] : null,
                    'images' => $service->images->map(fn ($image) => [
                        'id' => $image->id,
                        'image_path' => $image->image_path,
                        'is_cover' => (bool) $image->is_cover,
                    ])->values(),
                ];
            });

        return response()->json([
            'icon' => 'success',
            'title' => 'Home data loaded',
            'data' => [
                'featured_providers' => $featuredProviders,
                'categories' => $categories,
                'services' => $services,
            ],
        ], 200);
    }
}
