<?php
use App\Http\Controllers\Api\Event\EventCrudController;
use App\Http\Controllers\Api\Course\CourseCrudController;
use App\Http\Controllers\Api\News\NewsCrudController;
use App\Http\Controllers\Api\Comment\CommentCrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/student/get_allCourseList/{limit?}/{page?}/{query?}', [CourseCrudController::class, 'AllInstituteCourseList']);
Route::get('/student/get_allEventList/{limit?}/{page?}/{query?}', [EventCrudController::class, 'AllInstituteEventList']);
Route::get('/student/get_allNewsList/{limit?}/{page?}/{query?}', [NewsCrudController::class, 'AllNewsList']);
Route::get('/student/get_allCommentList/{limit?}/{page?}/{query?}', [CommentCrudController::class, 'AllCommentList']);
Route::delete('/student/delete/{event_id}', [CommentCrudController::class, 'delete']);
