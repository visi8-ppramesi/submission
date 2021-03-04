<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');
    Route::get('/submission/{submission}', [SubmissionController::class, 'show'])->name('submission.show');

    // Route::post('/submission/submit', [SubmissionController::class, 'store'])->name('submission.store');
});
