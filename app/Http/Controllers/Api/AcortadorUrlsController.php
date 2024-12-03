<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AcortadorUrlsController extends Controller
{
    const TINY_URL = "https://tinyurl.com/api-create.php";

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

        try {
            $tinyUrl = self::TINY_URL . "?url={$request->input('url')}";
            $response = Http::get($tinyUrl);
        } catch (Exception $e) {
            report($e); // Guardamos el log con el error en nuestro fichero de logs

            return response()->json([
                'error' => "Something went wrong while trying to short the url"
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'url' => $response->body()
        ], Response::HTTP_OK);
    }
}
