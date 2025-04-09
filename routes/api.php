<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Subject API routes
Route::get('/subjects/{id}/components', [\App\Http\Controllers\Api\SubjectController::class, 'getComponents']);

// Teacher API routes
Route::prefix('teacher')->group(function () {
    // Grade API routes
    Route::get('/grades/get-assessment-max-score', [\App\Http\Controllers\Teacher\GradeController::class, 'getAssessmentMaxScore']);
});