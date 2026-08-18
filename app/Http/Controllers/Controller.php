<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Farah API Documentation",
    description: "توثيق واجهات برمجة التطبيقات لمنصة فرح"
)]
#[OA\Server(
    url: "https://farah-backend-1.onrender.com/api",
    description: "سيرفر الإنتاج (Render)"
)]
#[OA\Server(
    url: "http://127.0.0.1:8000/api",
    description: "السيرفر المحلي"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    name: "Authorization",
    in: "header",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
#[OA\PathItem(path: "/api")]
abstract class Controller
{
    //
}