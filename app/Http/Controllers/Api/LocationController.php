<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LocationRequest;
use App\Models\Location;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Locations", description: "إدارة عناوين ومواقع المستخدمين")]
class LocationController extends Controller
{
    #[OA\Get(
        path: "/api/locations",
        summary: "جلب قائمة عناوين المستخدم الحالي",
        security: [["bearerAuth" => []]],
        tags: ["Locations"],
        responses: [
            new OA\Response(
                response: 200,
                description: "تم استرجاع العناوين بنجاح",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "success"),
                        new OA\Property(property: "title", type: "string", example: "Locations retrieved successfully."),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(type: "object"))
                    ]
                )
            ),
            new OA\Response(response: 401, description: "غير مصرح")
        ]
    )]
    public function index(Request $request)
    {
        $locations = $request->user()->locations()->with('city')->get();

        return response()->json([
            'icon' => 'success',
            'title' => 'Locations retrieved successfully.',
            'data' => $locations,
        ], 200);
    }

    #[OA\Post(
        path: "/api/locations",
        summary: "إضافة عنوان جديد للمستخدم الحالي",
        security: [["bearerAuth" => []]],
        tags: ["Locations"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "label", type: "string", example: "المنزل"),
                    new OA\Property(property: "address", type: "string", example: "شارع الجلاء، غزة"),
                    new OA\Property(property: "city_id", type: "integer", example: 1),
                    new OA\Property(property: "latitude", type: "number", format: "float", example: 31.5000),
                    new OA\Property(property: "longitude", type: "number", format: "float", example: 34.4667)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "تم إنشاء العنوان بنجاح"),
            new OA\Response(response: 422, description: "خطأ في البيانات المدخلة"),
            new OA\Response(response: 401, description: "غير مصرح")
        ]
    )]
    public function store(LocationRequest $request)
    {
        $location = $request->user()->locations()->create($request->validated());

        return response()->json([
            'icon' => 'success',
            'title' => 'Location created successfully.',
            'data' => $location,
        ], 201);
    }

    #[OA\Get(
        path: "/api/locations/{id}",
        summary: "عرض تفاصيل عنوان محدد",
        security: [["bearerAuth" => []]],
        tags: ["Locations"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "معرّف العنوان",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "تم استرجاع العنوان بنجاح"),
            new OA\Response(response: 403, description: "العنوان غير تابع للمستخدم الحالي"),
            new OA\Response(response: 404, description: "العنوان غير موجود"),
            new OA\Response(response: 401, description: "غير مصرح")
        ]
    )]
    public function show(Request $request, Location $location)
    {
        $this->authorizeLocation($request, $location);

        return response()->json([
            'icon' => 'success',
            'title' => 'Location retrieved successfully.',
            'data' => $location->load('city'),
        ], 200);
    }

    #[OA\Put(
        path: "/api/locations/{id}",
        summary: "تعديل عنوان محدد",
        security: [["bearerAuth" => []]],
        tags: ["Locations"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "معرّف العنوان",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "label", type: "string", example: "العمل"),
                    new OA\Property(property: "address", type: "string", example: "شارع الرمال، غزة"),
                    new OA\Property(property: "city_id", type: "integer", example: 1),
                    new OA\Property(property: "latitude", type: "number", format: "float", example: 31.5100),
                    new OA\Property(property: "longitude", type: "number", format: "float", example: 34.4500)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "تم تحديث العنوان بنجاح"),
            new OA\Response(response: 403, description: "العنوان غير تابع للمستخدم الحالي"),
            new OA\Response(response: 422, description: "خطأ في البيانات المدخلة"),
            new OA\Response(response: 401, description: "غير مصرح")
        ]
    )]
    public function update(LocationRequest $request, Location $location)
    {
        $this->authorizeLocation($request, $location);

        $location->update($request->validated());

        return response()->json([
            'icon' => 'success',
            'title' => 'Location updated successfully.',
            'data' => $location,
        ], 200);
    }

    #[OA\Delete(
        path: "/api/locations/{id}",
        summary: "حذف عنوان محدد",
        security: [["bearerAuth" => []]],
        tags: ["Locations"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                description: "معرّف العنوان",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "تم حذف العنوان بنجاح"),
            new OA\Response(response: 403, description: "العنوان غير تابع للمستخدم الحالي"),
            new OA\Response(response: 404, description: "العنوان غير موجود"),
            new OA\Response(response: 401, description: "غير مصرح")
        ]
    )]
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
