<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
/*
وظيفته

جلب كل التصنيفات

أو تصنيف معين حسب الحاجة

*/
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'image' => $category->image,
                ];
            });

        return response()->json([
            'icon' => 'success',
            'title' => 'Categories loaded',
            'data' => $categories,
        ], 200);
    }
}
