<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Services", description: "إدارة واستعراض الخدمات المقدمة في المنصة")]
class ServiceController extends Controller
{
    #[OA\Get(
        path: "/api/services",
        summary: "استعراض قائمة الخدمات مع الفلترة والترقيم",
        tags: ["Services"],
        parameters: [
            new OA\Parameter(
                name: "category_id",
                in: "query",
                description: "معرّف التصنيف للفلترة",
                required: false,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
            new OA\Parameter(
                name: "search",
                in: "query",
                description: "كلمة البحث في عنوان أو وصف الخدمة",
                required: false,
                schema: new OA\Schema(type: "string", example: "تصوير")
            ),
            new OA\Parameter(
                name: "page",
                in: "query",
                description: "رقم الصفحة للترقيم (Pagination)",
                required: false,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "تم جلب الخدمات بنجاح",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "current_page", type: "integer", example: 1),
                                new OA\Property(
                                    property: "data",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "category_id", type: "integer", example: 2),
                                            new OA\Property(property: "provider_id", type: "integer", example: 4),
                                            new OA\Property(property: "title", type: "string", example: "خدمة تصوير وتوثيق الحفلات"),
                                            new OA\Property(property: "description", type: "string", example: "تغطية شاملة وفيديو بدقة عالية"),
                                            new OA\Property(property: "price", type: "number", format: "float", example: 250.00),
                                            new OA\Property(property: "status", type: "string", example: "active"),
                                            new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2026-08-26T12:00:00.000000Z"),
                                            new OA\Property(
                                                property: "category",
                                                type: "object",
                                                properties: [
                                                    new OA\Property(property: "id", type: "integer", example: 2),
                                                    new OA\Property(property: "name", type: "string", example: "تصوير وفيديو")
                                                ]
                                            ),
                                            new OA\Property(
                                                property: "provider",
                                                type: "object",
                                                properties: [
                                                    new OA\Property(property: "id", type: "integer", example: 4),
                                                    new OA\Property(
                                                        property: "user",
                                                        type: "object",
                                                        properties: [
                                                            new OA\Property(property: "id", type: "integer", example: 10),
                                                            new OA\Property(property: "name", type: "string", example: "أحمد مصور")
                                                        ]
                                                    )
                                                ]
                                            ),
                                            new OA\Property(
                                                property: "images",
                                                type: "array",
                                                items: new OA\Items(
                                                    properties: [
                                                        new OA\Property(property: "id", type: "integer", example: 1),
                                                        new OA\Property(property: "image_path", type: "string", example: "services/image1.jpg")
                                                    ]
                                                )
                                            ),
                                            new OA\Property(
                                                property: "reviews",
                                                type: "array",
                                                items: new OA\Items(
                                                    properties: [
                                                        new OA\Property(property: "id", type: "integer", example: 1),
                                                        new OA\Property(property: "rating", type: "integer", example: 5),
                                                        new OA\Property(property: "comment", type: "string", example: "خدمة ممتازة جداً"),
                                                        new OA\Property(
                                                            property: "user",
                                                            type: "object",
                                                            properties: [
                                                                new OA\Property(property: "id", type: "integer", example: 8),
                                                                new OA\Property(property: "name", type: "string", example: "محمد علي")
                                                            ]
                                                        )
                                                    ]
                                                )
                                            )
                                        ]
                                    )
                                ),
                                new OA\Property(property: "first_page_url", type: "string", example: "http://example.com/api/services?page=1"),
                                new OA\Property(property: "from", type: "integer", example: 1),
                                new OA\Property(property: "last_page", type: "integer", example: 3),
                                new OA\Property(property: "last_page_url", type: "string", example: "http://example.com/api/services?page=3"),
                                new OA\Property(property: "next_page_url", type: "string", example: "http://example.com/api/services?page=2"),
                                new OA\Property(property: "path", type: "string", example: "http://example.com/api/services"),
                                new OA\Property(property: "per_page", type: "integer", example: 12),
                                new OA\Property(property: "prev_page_url", type: "string", nullable: true, example: null),
                                new OA\Property(property: "to", type: "integer", example: 12),
                                new OA\Property(property: "total", type: "integer", example: 30)
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function index(Request $request)
    {
        $query = Service::with(['category', 'provider', 'images', 'reviews.user'])
            ->where('status', 'active');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }

        $services = $query->latest()->paginate(12);

        return response()->json([
            'data' => $services,
        ]);
    }

    #[OA\Get(
        path: "/api/services/{id}",
        summary: "عرض تفاصيل خدمة محددة",
        tags: ["Services"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "معرّف الخدمة",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "تم جلب تفاصيل الخدمة بنجاح",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "category_id", type: "integer", example: 2),
                                new OA\Property(property: "provider_id", type: "integer", example: 4),
                                new OA\Property(property: "title", type: "string", example: "خدمة تصوير وتوثيق الحفلات"),
                                new OA\Property(property: "description", type: "string", example: "تغطية شاملة وفيديو بدقة عالية"),
                                new OA\Property(property: "price", type: "number", format: "float", example: 250.00),
                                new OA\Property(property: "status", type: "string", example: "active"),
                                new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2026-08-26T12:00:00.000000Z"),
                                new OA\Property(
                                    property: "category",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 2),
                                        new OA\Property(property: "name", type: "string", example: "تصوير وفيديو")
                                    ]
                                ),
                                new OA\Property(
                                    property: "provider",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 4),
                                        new OA\Property(property: "name", type: "string", example: "أحمد مصور"),
                                        new OA\Property(property: "email", type: "string", example: "ahmed@example.com")
                                    ]
                                ),
                                new OA\Property(
                                    property: "images",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "image_path", type: "string", example: "services/image1.jpg")
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: "reviews",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "rating", type: "integer", example: 5),
                                            new OA\Property(property: "comment", type: "string", example: "خدمة ممتازة جداً"),
                                            new OA\Property(
                                                property: "user",
                                                type: "object",
                                                properties: [
                                                    new OA\Property(property: "id", type: "integer", example: 8),
                                                    new OA\Property(property: "name", type: "string", example: "محمد علي")
                                                ]
                                            )
                                        ]
                                    )
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "الخدمة غير موجودة"
            )
        ]
    )]
    public function show($id)
    {
        $service = Service::with(['category', 'provider', 'images', 'reviews.user'])
            ->where('status', 'active')
            ->findOrFail($id);

        return response()->json([
            'data' => $service,
        ]);
    }
}