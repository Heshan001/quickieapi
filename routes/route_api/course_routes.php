<?php

use App\Http\Controllers\Api\Course\CourseCrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/course/store', [CourseCrudController::class, 'store']);
    Route::delete('/course/delete/{course_id}', [CourseCrudController::class, 'delete']);
    Route::get('/course/get_list/{limit?}/{page?}/{query?}', [CourseCrudController::class, 'instituteCourseList']);
});
