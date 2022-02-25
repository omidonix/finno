<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/iban/create', [\App\Models\User::class, 'storeIban']);

    Route::post('/pay/transfer_to', [\App\Http\Controllers\PaymentApiController::class, 'transferTo']);
    Route::get('/pay/transactions/{uuid}', [\App\Http\Controllers\PaymentApiController::class, 'transactions']);

});


