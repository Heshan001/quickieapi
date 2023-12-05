<?php
use App\Http\Controllers\Api\Event\EventCrudController;
use App\Http\Controllers\Api\Course\CourseCrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/student/get_allCourseList/{limit?}/{page?}/{query?}', [CourseCrudController::class, 'AllInstituteCourseList']);
Route::get('/student/get_allEventList/{limit?}/{page?}/{query?}', [EventCrudController::class, 'AllInstituteEventList']);
