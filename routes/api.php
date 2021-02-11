<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyEmailController;
use Illuminate\Support\Facades\Route;

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
});
Route::group([
    'prefix' => 'companies',
    'middleware' => []
], function () {
    Route::post('register', [CompanyController::class,'create']);
    Route::post('get/apikey', [CompanyController::class,'getApiKey'])->middleware('auth:api');
});

Route::group([
    'prefix' => 'mails',
    'middleware' => ['verify.key','auth:api']
], function () {
    Route::post('send', [CompanyEmailController::class,'send']);
    Route::post('get-all', [CompanyEmailController::class,'getAll']);
    Route::post('get-recipient', [CompanyEmailController::class,'getRecipient']);
    Route::get('get-single/{id}', [CompanyEmailController::class,'getSingle'])->where('id', '[0-9]+');
    Route::get('distinct-data', [CompanyEmailController::class,'getDistinct']);
});
