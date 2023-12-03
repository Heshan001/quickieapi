<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



Route::group([], base_path('routes/route_api/login_signup_routes.php'));
Route::group([], base_path('routes/route_api/course_routes.php'));
Route::group([], base_path('routes/route_api/event_routes.php'));

Route::post('/check', [AuthController::class, 'checkUserAuthOrNot']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [AuthController::class,'user']);
});
