<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AcortadorUrlsController extends Controller
{
    public function acortadorUrl(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => "Param 'url' is mandatory and must be a valid url"
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'message' => 'Hello World'
        ], Response::HTTP_OK);
    }
}
