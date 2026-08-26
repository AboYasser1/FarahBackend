<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Get(
        path: "/api/users",
        summary: "جلب قائمة المستخدمين",
        security: [["bearerAuth" => []]],
        tags: ["Users"],
        responses: [
            new OA\Response(
                response: 200,
                description: "تمت العملية بنجاح"
            ),
            new OA\Response(
                response: 401,
                description: "غير مصرح"
            )
        ]
    )]
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => []
        ]);
    }
}