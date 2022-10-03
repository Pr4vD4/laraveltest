<?php

use App\Http\Controllers\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/reg', [AuthController::class, 'registration']);
Route::post('/auth', [AuthController::class, 'auth']);


Route::group(['middleware' => 'auth:sanctum'], function (){
    Route::get('/info', function (){
        return response()->json(\Illuminate\Support\Facades\Auth::user());
    });
    Route::post('/post', [\App\Http\Controllers\PostController::class, 'store']);
    Route::get('/post', [\App\Http\Controllers\PostController::class, 'index']);
});
