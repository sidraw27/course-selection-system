<?php

use App\Http\Controllers\CourseAssistantController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentSelectionCourseController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1', 'middleware' => 'auth.api'], function () {
    Route::apiResource('teachers', TeacherController::class, [
        'only' => [
            'store',
            'update',
            'destroy'
        ]
    ]);
    Route::group(['prefix' => 'students'], function () {
        Route::apiResource('{students}/selection-courses', StudentSelectionCourseController::class, [
            'only' => [
                'store',
                'destroy',
            ]
        ]);
    });
    Route::apiResource('students', StudentController::class, [
        'only' => [
            'store',
            'update',
            'destroy'
        ]
    ]);
    Route::group(['prefix' => 'courses'], function () {
        Route::get('{courses}/students', [CourseController::class, 'students']);
        Route::apiResource('{courses}/assistants', CourseAssistantController::class, [
            'only' => [
                'update',
                'destroy',
            ]
        ]);
    });
    Route::apiResource('courses', CourseController::class, [
        'only' => [
            'show',
            'store',
            'update',
            'destroy'
        ]
    ]);
});
