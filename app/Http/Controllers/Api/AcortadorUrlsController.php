<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AcortadorUrlsController extends Controller
{
    public function acortadorUrl(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Hello World'
        ], Response::HTTP_OK);
    }
}
