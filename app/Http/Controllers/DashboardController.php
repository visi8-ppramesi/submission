<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function showDashboard(){
        $user = auth()->user();
        $subs = Submission::where('user_id', $user->id)->get();
        return Inertia::render('Dashboard', [
            'submissions' => $subs
        ]);
    }
}
