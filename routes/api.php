<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;


Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/blogs/list', [BlogController::class, 'index']);
    Route::post('/blogs', [BlogController::class, 'store']);
    
    Route::post('/blogs/update/{id}', [BlogController::class, 'update']);

    Route::post('/blogs/delete/{id}', [BlogController::class, 'destroy']);
    
    Route::post('/blogs/{id}/like-toggle', [BlogController::class, 'toggleLike']);
});
