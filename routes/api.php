<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AcortadorUrlsController;

Route::prefix('v1')->group(function () {
    Route::post('short-urls', [
        AcortadorUrlsController::class, 'acortadorUrl'
    ]);
});