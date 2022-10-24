<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\UserApiController;
use App\Http\Controllers\Api\FileApiController;
use App\Http\Controllers\Api\UsageApiController;

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
Route::post('login',                [UserApiController::class,     'login']);

Route::middleware(['auth:api'])->group(function () {

    Route::get('/access',           [UserApiController::class,     'access']);

    
    Route::middleware(['usage'])->group(function () {

        //API FILES
        Route::prefix('files')->group(function () {
            Route::controller(FileApiController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::post('/multiple-files', 'multipleStore');
                Route::delete('/{file}/type/{type}', 'destroy');
            });
        });

        //API USAGE
        Route::prefix('usages')->group(function () {
            Route::controller(UsageApiController::class)->group(function () {
                Route::get('/', 'index');
            });
        });
    });
    

});