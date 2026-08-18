<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProviderProfile;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProviders = ProviderProfile::with('user')->where('is_featured', true)->limit(6)->get();
        $categories = Category::query()->where('status', 'active')->limit(8)->get();
        $services = Service::with(['category', 'provider', 'images'])->where('status', 'active')->limit(8)->get();

        return response()->json([
            'featured_providers' => $featuredProviders,
            'categories' => $categories,
            'services' => $services,
        ]);
    }
}
