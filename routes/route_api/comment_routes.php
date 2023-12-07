<?php

use App\Http\Controllers\Api\Comment\CommentCrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/comment/store', [CommentCrudController::class, 'store']);

});
