<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()->where('status', 'active')->get();

        return response()->json([
            'data' => $categories,
        ]);
    }
}
