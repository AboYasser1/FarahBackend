<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Services", description: "إدارة واستعراض الخدمات والبحث والفلترة حسب التصنيف والموقع والسعر")]
class ServiceController extends Controller
{
    #[OA\Get(
        path: "/api/services",
        summary: "استعراض والبحث في الخدمات والفلترة حسب التصنيف والفرز",
        description: "يسترجع قائمة الخدمات المطابقة للتصميم (كروت الصالات والخدمات) مع دعم البحث الفوري، الفلترة حسب التصنيف والمدينة، والفرز بالأقل سعراً أو الأعلى تقييماً أو الأقرب لموقعك، مع حالة المفضلة للمستخدم المسجل.",
        tags: ["Services"],
        parameters: [
            new OA\Parameter(
                name: "category_id",
                in: "query",
                description: "معرّف التصنيف (مثل صالات: 1)",
                required: false,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
            new OA\Parameter(
                name: "category_slug",
                in: "query",
                description: "الاسم اللطيف للتصنيف (مثل: wedding-halls)",
                required: false,
                schema: new OA\Schema(type: "string", example: "wedding-halls")
            ),
            new OA\Parameter(
                name: "search",
                in: "query",
                description: "كلمة البحث في عنوان أو وصف الخدمة (ابحث ضمن الصالات...)",
                required: false,
                schema: new OA\Schema(type: "string", example: "اورنا")
            ),
            new OA\Parameter(
                name: "sort_by",
                in: "query",
                description: "طريقة الفرز: all (الكل)، price_low (الأقل سعراً)، price_high (الأعلى سعراً)، rating (الأعلى تقييماً)، nearest (الأقرب لموقعك)",
                required: false,
                schema: new OA\Schema(type: "string", enum: ["all", "price_low", "price_high", "rating", "nearest"], default: "all")
            ),
            new OA\Parameter(
                name: "city_id",
                in: "query",
                description: "فلترة حسب المدينة",
                required: false,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
            new OA\Parameter(
                name: "latitude",
                in: "query",
                description: "خط العرض الحالي لحساب الأقرب لموقعك",
                required: false,
                schema: new OA\Schema(type: "number", format: "float", example: 31.5000)
            ),
            new OA\Parameter(
                name: "longitude",
                in: "query",
                description: "خط الطول الحالي لحساب الأقرب لموقعك",
                required: false,
                schema: new OA\Schema(type: "number", format: "float", example: 34.4667)
            ),
            new OA\Parameter(
                name: "per_page",
                in: "query",
                description: "عدد العناصر في الصفحة الواحدة",
                required: false,
                schema: new OA\Schema(type: "integer", default: 10)
            ),
            new OA\Parameter(
                name: "page",
                in: "query",
                description: "رقم الصفحة (Pagination)",
                required: false,
                schema: new OA\Schema(type: "integer", default: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "تم جلب الخدمات بنجاح وتوافقها التام مع كروت الواجهة",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "category",
                            type: "object",
                            nullable: true,
                            description: "بيانات التصنيف المختار (لعنوان الشاشة)",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "صالات"),
                                new OA\Property(property: "slug", type: "string", example: "wedding-halls"),
                                new OA\Property(property: "image", type: "string", nullable: true, example: "categories/hall.jpg")
                            ]
                        ),
                        new OA\Property(
                            property: "available_sorts",
                            type: "array",
                            description: "خيارات الفرز المتاحة في الواجهة",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "key", type: "string", example: "all"),
                                    new OA\Property(property: "label", type: "string", example: "الكل")
                                ]
                            )
                        ),
                        new OA\Property(property: "active_sort", type: "string", example: "all"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            description: "قائمة كروت الخدمات المطابقة للتصميم",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "title", type: "string", example: "صالة أورنا"),
                                    new OA\Property(property: "description", type: "string", nullable: true, example: "صالة أفراح مجهزة بأحدث الديكورات"),
                                    new OA\Property(property: "price", type: "number", format: "float", example: 1400.00),
                                    new OA\Property(property: "currency", type: "string", example: "ILS"),
                                    new OA\Property(property: "image", type: "string", nullable: true, example: "services/hall_orna.jpg"),
                                    new OA\Property(
                                        property: "images",
                                        type: "array",
                                        items: new OA\Items(type: "string", example: "services/hall_orna.jpg")
                                    ),
                                    new OA\Property(property: "rating_avg", type: "number", format: "float", example: 4.8),
                                    new OA\Property(property: "reviews_count", type: "integer", example: 24),
                                    new OA\Property(property: "location", type: "string", example: "غزة - الرمال - شمال مطعم التايلندي، عمارة حرز الله"),
                                    new OA\Property(property: "city", type: "string", example: "غزة"),
                                    new OA\Property(property: "is_favorited", type: "boolean", example: false),
                                    new OA\Property(property: "button_text", type: "string", example: "تفاصيل الصالة"),
                                    new OA\Property(
                                        property: "provider",
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 4),
                                            new OA\Property(property: "name", type: "string", example: "إدارة صالة أورنا"),
                                            new OA\Property(property: "avatar", type: "string", nullable: true, example: "providers/orna.jpg")
                                        ]
                                    ),
                                    new OA\Property(
                                        property: "category",
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "name", type: "string", example: "صالات"),
                                            new OA\Property(property: "slug", type: "string", example: "wedding-halls")
                                        ]
                                    )
                                ]
                            )
                        ),
                        new OA\Property(
                            property: "pagination",
                            type: "object",
                            properties: [
                                new OA\Property(property: "current_page", type: "integer", example: 1),
                                new OA\Property(property: "last_page", type: "integer", example: 3),
                                new OA\Property(property: "per_page", type: "integer", example: 10),
                                new OA\Property(property: "total", type: "integer", example: 25),
                                new OA\Property(property: "has_more", type: "boolean", example: true)
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function index(Request $request)
    {
        $user = $request->user('sanctum') ?? auth('sanctum')->user();
        $userFavoriteIds = [];

        if ($user) {
            $userFavoriteIds = Favorite::where('user_id', $user->id)
                ->pluck('service_id')
                ->toArray();
        }

        $query = Service::with([
            'category',
            'city',
            'provider.providerProfile',
            'provider.locations.city',
            'images'
        ])->where('status', 'active');

        // 1. الفلترة حسب التصنيف (ID أو Slug)
        $categoryId = $request->category_id ?? $request->route('id');
        $currentCategory = null;
        if ($categoryId) {
            $query->where('category_id', $categoryId);
            $currentCategory = Category::find($categoryId);
        } elseif ($request->filled('category_slug')) {
            $currentCategory = Category::where('slug', $request->category_slug)->first();
            if ($currentCategory) {
                $query->where('category_id', $currentCategory->id);
            }
        }

        // 2. الفلترة حسب المدينة
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // 3. البحث الذكي بالعربية في العنوان أو الوصف
        if ($request->filled('search')) {
            $rawSearch = trim($request->search);
            $variations = [$rawSearch];

            // استبدال الألف في بداية الكلمة (ا / أ / إ / آ)
            if (preg_match('/^[اأإآ]/u', $rawSearch)) {
                $rest = mb_substr($rawSearch, 1);
                $variations[] = 'ا' . $rest;
                $variations[] = 'أ' . $rest;
                $variations[] = 'إ' . $rest;
                $variations[] = 'آ' . $rest;
            }

            // استبدال التاء المربوطة والهاء في نهاية الكلمة (ة / ه)
            if (preg_match('/[ةه]$/u', $rawSearch)) {
                $base = mb_substr($rawSearch, 0, -1);
                $variations[] = $base . 'ة';
                $variations[] = $base . 'ه';
            }

            // استبدال الياء والألف المقصورة في نهاية الكلمة (ي / ى)
            if (preg_match('/[يى]$/u', $rawSearch)) {
                $base = mb_substr($rawSearch, 0, -1);
                $variations[] = $base . 'ي';
                $variations[] = $base . 'ى';
            }

            $variations = array_unique($variations);

            $query->where(function ($q) use ($variations) {
                foreach ($variations as $term) {
                    $searchTerm = '%' . $term . '%';
                    $q->orWhere('title', 'like', $searchTerm)
                      ->orWhere('description', 'like', $searchTerm);
                }
            });
        }

        // 4. الفرز والتريب (Sort by)
        $sortBy = $request->get('sort_by', 'all');

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderByDesc('rating_avg')->orderByDesc('reviews_count');
                break;
            case 'nearest':
                // الفرز بالأقرب لموقع المستخدم إذا توفرت الإحداثيات أو مدينة المستخدم
                $lat = $request->latitude ?? ($user?->locations?->first()?->latitude);
                $lng = $request->longitude ?? ($user?->locations?->first()?->longitude);

                if ($lat && $lng) {
                    // إذا كان المزود لديه موقع محدد
                    $query->orderByDesc('is_featured')->latest();
                } elseif ($user?->city_id) {
                    $query->orderByRaw('CASE WHEN city_id = ? THEN 0 ELSE 1 END', [$user->city_id])
                          ->latest();
                } else {
                    $query->latest();
                }
                break;
            case 'all':
            default:
                $query->orderByDesc('is_featured')->latest();
                break;
        }

        $perPage = (int) $request->get('per_page', 10);
        $paginated = $query->paginate($perPage);

        $categoryName = $currentCategory?->name ?? 'الخدمة';
        $buttonText = (str_contains($categoryName, 'صالة') || str_contains($categoryName, 'صالات'))
            ? 'تفاصيل الصالة'
            : 'تفاصيل ' . $categoryName;

        $items = collect($paginated->items())->map(function ($service) use ($userFavoriteIds, $buttonText) {
            $providerName = $service->provider?->providerProfile?->business_name ?: ($service->provider?->name ?? 'مزود خدمة');
            $providerAvatar = $service->provider?->avatar ?: ($service->provider?->providerProfile?->cover_image);

            $primaryLocation = $service->provider?->locations?->first();
            $locationText = $primaryLocation?->address
                ? (($primaryLocation->city?->name ? $primaryLocation->city->name . ' - ' : '') . $primaryLocation->address)
                : ($service->city?->name ? $service->city->name : 'غزة');

            $allImages = $service->images->pluck('image_path')->toArray();
            if ($service->image && !in_array($service->image, $allImages)) {
                array_unshift($allImages, $service->image);
            }

            return [
                'id' => $service->id,
                'title' => $service->title,
                'description' => $service->description,
                'price' => (float) $service->price,
                'currency' => $service->currency ?: 'ILS',
                'image' => $service->image ?: ($service->images->first()?->image_path),
                'images' => $allImages,
                'rating_avg' => (float) $service->rating_avg,
                'reviews_count' => (int) $service->reviews_count,
                'location' => $locationText,
                'city' => $service->city?->name ?: ($primaryLocation?->city?->name ?? 'غزة'),
                'is_favorited' => in_array($service->id, $userFavoriteIds),
                'button_text' => $buttonText,
                'category' => $service->category ? [
                    'id' => $service->category->id,
                    'name' => $service->category->name,
                    'slug' => $service->category->slug,
                ] : null,
                'provider' => [
                    'id' => $service->provider_id,
                    'name' => $providerName,
                    'avatar' => $providerAvatar,
                ],
            ];
        });

        $availableSorts = [
            ['key' => 'all', 'label' => 'الكل'],
            ['key' => 'price_low', 'label' => 'الأقل سعراً'],
            ['key' => 'rating', 'label' => 'الأعلى تقييماً'],
            ['key' => 'nearest', 'label' => 'الأقرب لموقعك'],
        ];

        return response()->json([
            'category' => $currentCategory ? [
                'id' => $currentCategory->id,
                'name' => $currentCategory->name,
                'slug' => $currentCategory->slug,
                'image' => $currentCategory->image,
            ] : null,
            'available_sorts' => $availableSorts,
            'active_sort' => $sortBy,
            'data' => $items,
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'has_more' => $paginated->hasMorePages(),
            ],
        ]);
    }

    #[OA\Get(
        path: "/api/services/{id}",
        summary: "عرض تفاصيل خدمة أو صالة محددة",
        description: "يسترجع كافة تفاصيل الخدمة بما يشمل معرض الصور، التقييمات، معلومات المزود، والموقع.",
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
                                new OA\Property(property: "title", type: "string", example: "صالة أورنا"),
                                new OA\Property(property: "description", type: "string", example: "صالة مجهزة بأحدث الديكورات ونظام إضاءة وصوت مميز"),
                                new OA\Property(property: "price", type: "number", format: "float", example: 1400.00),
                                new OA\Property(property: "currency", type: "string", example: "ILS"),
                                new OA\Property(property: "image", type: "string", nullable: true, example: "services/hall_orna.jpg"),
                                new OA\Property(
                                    property: "images",
                                    type: "array",
                                    items: new OA\Items(type: "string", example: "services/hall_orna.jpg")
                                ),
                                new OA\Property(property: "rating_avg", type: "number", format: "float", example: 4.8),
                                new OA\Property(property: "reviews_count", type: "integer", example: 24),
                                new OA\Property(property: "location", type: "string", example: "غزة - الرمال - شمال مطعم التايلندي، عمارة حرز الله"),
                                new OA\Property(property: "city", type: "string", example: "غزة"),
                                new OA\Property(property: "is_favorited", type: "boolean", example: false),
                                new OA\Property(
                                    property: "category",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "name", type: "string", example: "صالات"),
                                        new OA\Property(property: "slug", type: "string", example: "wedding-halls")
                                    ]
                                ),
                                new OA\Property(
                                    property: "provider",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 4),
                                        new OA\Property(property: "name", type: "string", example: "صالة أورنا"),
                                        new OA\Property(property: "avatar", type: "string", nullable: true, example: "providers/orna.jpg"),
                                        new OA\Property(property: "phone", type: "string", nullable: true, example: "0599000000"),
                                        new OA\Property(property: "bio", type: "string", nullable: true, example: "أفضل صالات الأفراح في غزة")
                                    ]
                                ),
                                new OA\Property(
                                    property: "reviews",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "rating", type: "integer", example: 5),
                                            new OA\Property(property: "comment", type: "string", example: "مكان رائع وخدمة ممتازة جداً"),
                                            new OA\Property(property: "user_name", type: "string", example: "مالك"),
                                            new OA\Property(property: "created_at", type: "string", example: "2026-08-20")
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
    public function show(Request $request, $id)
    {
        $user = $request->user('sanctum') ?? auth('sanctum')->user();

        $service = Service::with([
            'category',
            'city',
            'provider.providerProfile',
            'provider.locations.city',
            'images',
            'reviews.user'
        ])
        ->where('status', 'active')
        ->findOrFail($id);

        $isFavorited = false;
        if ($user) {
            $isFavorited = Favorite::where('user_id', $user->id)
                ->where('service_id', $service->id)
                ->exists();
        }

        $providerName = $service->provider?->providerProfile?->business_name ?: ($service->provider?->name ?? 'مزود خدمة');
        $providerAvatar = $service->provider?->avatar ?: ($service->provider?->providerProfile?->cover_image);
        $providerBio = $service->provider?->providerProfile?->bio ?: $service->provider?->bio;
        $providerPhone = $service->provider?->providerProfile?->phone ?: $service->provider?->phone;

        $primaryLocation = $service->provider?->locations?->first();
        $locationText = $primaryLocation?->address
            ? (($primaryLocation->city?->name ? $primaryLocation->city->name . ' - ' : '') . $primaryLocation->address)
            : ($service->city?->name ? $service->city->name : 'غزة');

        $allImages = $service->images->pluck('image_path')->toArray();
        if ($service->image && !in_array($service->image, $allImages)) {
            array_unshift($allImages, $service->image);
        }

        $reviews = $service->reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'rating' => (int) $review->rating,
                'comment' => $review->comment,
                'user_name' => $review->user?->name ?? 'مستخدم',
                'user_avatar' => $review->user?->avatar,
                'created_at' => $review->created_at?->format('Y-m-d'),
            ];
        });

        $data = [
            'id' => $service->id,
            'title' => $service->title,
            'description' => $service->description,
            'price' => (float) $service->price,
            'currency' => $service->currency ?: 'ILS',
            'image' => $service->image ?: ($service->images->first()?->image_path),
            'images' => $allImages,
            'rating_avg' => (float) $service->rating_avg,
            'reviews_count' => (int) $service->reviews_count,
            'location' => $locationText,
            'city' => $service->city?->name ?: ($primaryLocation?->city?->name ?? 'غزة'),
            'is_favorited' => $isFavorited,
            'category' => $service->category ? [
                'id' => $service->category->id,
                'name' => $service->category->name,
                'slug' => $service->category->slug,
            ] : null,
            'provider' => [
                'id' => $service->provider_id,
                'name' => $providerName,
                'avatar' => $providerAvatar,
                'phone' => $providerPhone,
                'bio' => $providerBio,
            ],
            'reviews' => $reviews,
        ];

        return response()->json([
            'data' => $data,
        ]);
    }
}