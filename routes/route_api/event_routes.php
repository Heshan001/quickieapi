<?php

use App\Http\Controllers\Api\Event\EventCrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/event/store', [EventCrudController::class, 'store']);
    Route::delete('/event/delete/{event_id}', [EventCrudController::class, 'delete']);
    Route::get('/event/get_list/{limit?}/{page?}/{query?}', [EventCrudController::class, 'instituteEventList']);
});

