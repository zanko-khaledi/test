<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');



Route::withoutMiddleware('auth')->group(function (){
    Route::post('/register', RegisterController::class)
        ->name('user.register');
    Route::post('/login', LoginController::class)
        ->name('user.login');
    Route::get('/logout',[LoginController::class,'logout'])
        ->name('user.logout');
});


Route::middleware([
    'auth:sanctum'
])->apiResource('tasks', TaskController::class);

Route::middleware('auth:sanctum')->patch('tasks/{task}/update-status',[TaskController::class,'updateStatus'])
    ->name('tasks.update_status');
