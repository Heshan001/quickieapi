<?php

use App\Http\Controllers\Api\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user/get_allUserList/{limit?}/{page?}/{query?}', [UserController::class, 'getAllUsersWithEmailsAndRoles']);

});
