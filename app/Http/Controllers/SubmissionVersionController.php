<?php

namespace App\Http\Controllers;

use App\Models\SubmissionVersion;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Submission $submission)
    {
        $userid = auth()->user()->id;
        $jsonObj = json_encode($submission->toArray());
        $subId = $submission->id;
        $curr = SubmissionVersion::where([
            ['submission_id', '=', $subId],
            ['current', '=', true]
        ])->first();
        $curr->update(['current' => false]);
        $newsubver = SubmissionVersion::create([
            'first' => false,
            'current' => true,
            'submission_items' => $jsonObj,
            'submission_id' => $subId,
            'previous_submission_version_id' => $curr->id,
            'user_id' => $userid,
        ]);

        return response()->json($newsubver, 200);
    }

    /**
     * Store a newly created subversion in storage for new sub.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFirst(Request $request, Submission $submission){
        $userid = auth()->user()->id;
        $jsonObj = json_encode($submission->toArray());
        $subId = $submission->id;
        $newsubver = SubmissionVersion::create([
            'first' => true,
            'current' => true,
            'submission_items' => $jsonObj,
            'submission_id' => $subId,
            'user_id' => $userid,
        ]);

        return response()->json($newsubver, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubmissionVersion  $submissionVersion
     * @return \Illuminate\Http\Response
     */
    public function show(SubmissionVersion $submissionVersion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubmissionVersion  $submissionVersion
     * @return \Illuminate\Http\Response
     */
    public function edit(SubmissionVersion $submissionVersion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubmissionVersion  $submissionVersion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubmissionVersion $submissionVersion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubmissionVersion  $submissionVersion
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubmissionVersion $submissionVersion)
    {
        //
    }
}
