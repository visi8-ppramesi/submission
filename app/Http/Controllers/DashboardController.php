<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\SubmissionVersion;
use App\Models\User;
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

    public function showAdminUser(User $user){
        return Inertia::render('Admin/Dashboard/User', [
            'user' => $user->with('submissions')->first()
        ]);
    }

    public function showAdminUsers(){
        return Inertia::render('Admin/Dashboard/Users', [
            'users' => User::all()
        ]);
    }

    public function showAdminSubmission(Submission $submission){
        $subver = SubmissionVersion::where([
            ['submission_id', '=', $submission->id],
            ['first', '=', true]
        ])
        ->first()
        ->getForwardVersions();
        return Inertia::render('Admin/Dashboard/Submission', [
            'submission' => $submission->with('submissionVersions')->first(),
            'versions' => $subver
        ]);
    }

    public function showAdminSubmissions(){
        return Inertia::render('Admin/Dashboard/Submissions', [
            'submissions' => Submission::with('user')->get()
        ]);
    }

    public function showAdminDashboard(){

    }
}
