<?php

use App\Http\Controllers\Api\News\NewsCrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/news/store', [NewsCrudController::class, 'store']);
    Route::delete('/news/delete/{course_id}', [NewsCrudController::class, 'delete']);
    Route::get('/news/get_list/{limit?}/{page?}/{query?}', [NewsCrudController::class, 'newsList']);

});
