<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SubmissionVersionController;
use App\Models\SubmissionVersion;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'permission:create-submission'])->group(function () {
    Route::post('/submission/submit/file', [SubmissionController::class, 'storeSubmissionFile'])->name('submission.store.file');
    Route::post('/submission/update/file/{submission}', [SubmissionController::class, 'updateSubmissionFile'])->name('submission.update.file');
    Route::post('/submission/submit', [SubmissionController::class, 'store'])->name('submission.store');
    Route::post('/submission/update/{submission}', [SubmissionController::class, 'update'])->name('submission.update');

    Route::post('/submissionversion/submit/first/{submission}', [SubmissionVersionController:: class, 'storeFirst'])->name('submissionversion.store.first');
    Route::post('/submissionversion/submit/{submission}', [SubmissionVersionController:: class, 'store'])->name('submissionversion.store');
});
Route::middleware(['role:admin', 'auth:sanctum'])->group(function(){
    Route::post('/testing', function(){
        return auth()->user();
        // return SubmissionVersion::find(1)->getForwardVersions();
    });
});
