<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('register', [UserController::class,'register']);
Route::post('login', [UserController::class,'authenticate']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('user', [UserController::class,'getAuthenticatedUser']);
    Route::post('refresh', [UserController::class,'refresh']);
    Route::post('logout', [UserController::class,'logout']);
    Route::put('updateprofile', [UserController::class,'updateProfile']);
    Route::get('plant',[PlantController::class,'index']);
    Route::get('/plant/{plant}',[PlantController::class,'show']);
});

Route::group(['middleware' => ['jwt.admin.verify']], function() {
    Route::put('/role/{user_id}', [UserController::class,"changeRole"]);
    Route::apiResource('category',CategoryController::class);
});

Route::group(['middleware' => ['jwt.seller.verify']], function() {
    Route::apiResource('plant',PlantController::class)->except(['index','show']);

});
