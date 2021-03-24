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
            'submissions' => $subs,
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
        $lastWeek = date('Y-m-d H:i:s', strtotime('-1 week'));
        $today = date('Y-m-d H:i:s', strtotime('today +1 day'));
        $recentUsers = User::whereBetween('created_at', [$lastWeek, $today])->orderBy('created_at', 'desc')->get();
        $recentSubs = Submission::whereBetween('created_at', [$lastWeek, $today])->orderBy('created_at', 'desc')->with('user')->get();
        $recentUpdateSubs = Submission::whereBetween('updated_at', [$lastWeek, $today])->orderBy('updated_at', 'desc')->with('user')->get();

        return Inertia::render('Admin/Dashboard', [
            'recentUsers' => $recentUsers,
            'recentSubs' => $recentSubs,
            'recentUpdateSubs' => $recentUpdateSubs,
            'aggregatedSubs' => Submission::getAggregatedRecords($lastWeek, $today, 'created_at')
        ]);
    }
}
