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
        $u = User::where('id', $user->id)
            ->with('submissions')
            ->first();
        return Inertia::render('Admin/Dashboard/User', [
            'user' => $u//$user->with('submissions')->first()
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
        $s = Submission::where('id', $submission->id)
            // ->with('submissionVersions')
            ->first();
        return Inertia::render('Admin/Dashboard/Submission', [
            'submission' => $s,
            'versions' => $subver
        ]);
    }

    public function showAdminSubmissions(){
        return Inertia::render('Admin/Dashboard/Submissions', [
            'submissions' => Submission::with('user')->get()
        ]);
    }

    public function showAdminDashboard(){
        return Inertia::render('Admin/Dashboard');
    }
}
