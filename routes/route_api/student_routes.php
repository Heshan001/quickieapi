<?php
use App\Http\Controllers\Api\Event\EventCrudController;
use App\Http\Controllers\Api\Course\CourseCrudController;
use App\Http\Controllers\Api\News\NewsCrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/student/get_allCourseList/{limit?}/{page?}/{query?}', [CourseCrudController::class, 'AllInstituteCourseList']);
Route::get('/student/get_allEventList/{limit?}/{page?}/{query?}', [EventCrudController::class, 'AllInstituteEventList']);
Route::get('/student/get_allNewsList/{limit?}/{page?}/{query?}', [NewsCrudController::class, 'AllNewsList']);
