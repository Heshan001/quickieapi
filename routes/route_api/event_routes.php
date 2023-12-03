<?php

use App\Http\Controllers\Api\Event\EventCrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/course/store', [EventCrudController::class, 'store']);
    Route::delete('/course/delete/{course_id}', [EventCrudController::class, 'delete']);
    Route::get('/course/get_list/{limit?}/{page?}/{query?}', [EventCrudController::class, 'instituteCourseList']);
});
