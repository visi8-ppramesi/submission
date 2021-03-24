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

Route::get('/testing', function(){
    return Inertia::render('Testing');
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        // 'hello' => phpinfo()
    ]);
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->name('dashboard');

Route::middleware(['auth:sanctum', 'verified', 'permission:view-own-submissions'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');
    Route::get('/dashboard/submission/{submission}', [SubmissionController::class, 'show'])->name('submission.show');

    // Route::post('/submission/submit', [SubmissionController::class, 'store'])->name('submission.store');
});

Route::middleware(['auth:sanctum', 'role:admin|judge'])->group(function(){
    Route::get('/dashboard/admin/user/{user}', [DashboardController::class, 'showAdminUser'])->name('dashboard.admin.user'); //show submissions here?
    Route::get('/dashboard/admin/users', [DashboardController::class, 'showAdminUsers'])->name('dashboard.admin.users');
    Route::get('/dashboard/admin/submission/{submission}', [DashboardController::class, 'showAdminSubmission'])->name('dashboard.admin.submission');
    Route::get('/dashboard/admin/submissions', [DashboardController::class, 'showAdminSubmissions'])->name('dashboard.admin.submissions');
    Route::get('/dashboard/admin', [DashboardController::class, 'showAdminDashboard'])->name('dashboard.admin');
});
