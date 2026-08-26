<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProviderProfile;
use App\Models\Service;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Home", description: "بيانات الصفحة الرئيسية للتطبيق")]
class HomeController extends Controller
{
    #[OA\Get(
        path: "/api/home",
        summary: "جلب بيانات الصفحة الرئيسية (المزودين المميزين، التصنيفات، الخدمات)",
        description: "يسترجع المكونات الأساسية للواجهة الرئيسية متضمنة المزودين الموصى بهم، الفئات، وأحدث الخدمات النشطة.",
        tags: ["Home"],
        responses: [
            new OA\Response(
                response: 200,
                description: "تم جلب بيانات الصفحة الرئيسية بنجاح",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "featured_providers",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "user_id", type: "integer", example: 4),
                                    new OA\Property(property: "bio", type: "string", example: "مختص بالتصوير والتنسيق"),
                                    new OA\Property(property: "is_featured", type: "boolean", example: true),
                                    new OA\Property(
                                        property: "user",
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 4),
                                            new OA\Property(property: "name", type: "string", example: "أحمد مصور"),
                                            new OA\Property(property: "email", type: "string", example: "ahmed@example.com")
                                        ]
                                    )
                                ]
                            )
                        ),
                        new OA\Property(
                            property: "categories",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "name", type: "string", example: "صالات أفراح"),
                                    new OA\Property(property: "slug", type: "string", example: "wedding-halls"),
                                    new OA\Property(property: "image", type: "string", nullable: true, example: "categories/hall.jpg"),
                                    new OA\Property(property: "status", type: "string", example: "active")
                                ]
                            )
                        ),
                        new OA\Property(
                            property: "services",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "category_id", type: "integer", example: 1),
                                    new OA\Property(property: "provider_id", type: "integer", example: 1),
                                    new OA\Property(property: "title", type: "string", example: "تنسيق قاعات ومهرجانات"),
                                    new OA\Property(property: "price", type: "number", format: "float", example: 150.00),
                                    new OA\Property(property: "status", type: "string", example: "active"),
                                    new OA\Property(
                                        property: "category",
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "name", type: "string", example: "صالات أفراح")
                                        ]
                                    ),
                                    new OA\Property(
                                        property: "provider",
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "bio", type: "string", example: "مختص بالتصوير والتنسيق")
                                        ]
                                    ),
                                    new OA\Property(
                                        property: "images",
                                        type: "array",
                                        items: new OA\Items(
                                            properties: [
                                                new OA\Property(property: "id", type: "integer", example: 1),
                                                new OA\Property(property: "image_path", type: "string", example: "services/service1.jpg")
                                            ]
                                        )
                                    )
                                ]
                            )
                        )
                    ]
                )
            )
        ]
    )]
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