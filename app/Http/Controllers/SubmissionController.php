<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\SubmissionVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SubmissionController extends Controller
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'team_profile' => ['required', 'string'],
        ]);

        $validated['user_id'] = auth()->user()->id;
        $validated['team_id'] = auth()->user()->currentTeam->id;

        return response()->json(Submission::create($validated), 200);
    }

    public function storeSubmissionFile(Request $request)
    {
        // $flip = mt_rand() / mt_getrandmax();
        // if($flip < 0.5){
        //     throw 'error';
        // }
        $column = $request['column'];
        // $user = auth()->user();
        $subId = $request['id'];
        $file = $request->file('file');
        $ogName = $file->getClientOriginalName();

        // if(Storage::exists('public/' . $column . '/' . $ogName)){
        $boom = explode('.', $ogName);
        $fileType = array_pop($boom);
        $randomStr = Str::random(15);

        // $startNum = 1;
        // $checkName = 'public/' . $column . '/' . implode($boom) . '(' . $startNum . ').' . $fileType;
        $checkName = 'public/' . $column . '/' . implode($boom) . '-' . $randomStr . '.' . $fileType;
        $numFileFound = Storage::exists($checkName);

        while($numFileFound){
            // $startNum++;
            // $checkName = 'public/' . $column . '/' . implode($boom) . '(' . $startNum . ').' . $fileType;
            $randomStr = Str::random(15);
            $checkName = 'public/' . $column . '/' . implode($boom) . '-' . $randomStr . '.' . $fileType;
            $numFileFound = Storage::exists($checkName);
        }
        $filepath = $file->storeAs('public/' . $column, $checkName);
        // }else{
        //     $filepath = $file->storeAs('public/' . $column, $ogName);
        // }

        // $filepath = $file->store('public/' . $column);
        $sub = Submission::find($subId);
        $sub[$column] = Storage::url($filepath);

        return response()->json($sub->save(), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function show(Submission $submission)
    {
        $subver = SubmissionVersion::where([
            ['submission_id', '=', $submission->id],
            ['first', '=', true]
        ])
        ->first()
        ->getForwardVersions();
        return Inertia::render('Submission', [
            'submission' => $submission,
            'versions' => $subver
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function edit(Submission $submission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Submission $submission)
    {
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'team_profile' => ['required', 'string'],
        ]);

        if(auth()->user()->id != $submission['user_id']){
            return response()->json(['error' => 'Unathorized.'], 401);
        }

        return response()->json($submission->update($validated), 200);
    }

    public function updateSubmissionFile(Request $request, Submission $submission){
        // $flip = mt_rand() / mt_getrandmax();
        // if($flip < 0.5){
        //     throw 'error';
        // }
        $column = $request['column'];
        // $user = auth()->user();
        $file = $request->file('file');
        $ogName = $file->getClientOriginalName();

        // if(Storage::exists('public/' . $column . '/' . $ogName)){
        $boom = explode('.', $ogName);
        $randomStr = Str::random(15);
        $fileType = array_pop($boom);
        // $oldRndStr = array_pop($boom);

        // $startNum = 1;
        $checkName = 'public/' . $column . '/' . implode($boom) . '-' . $randomStr . '.' . $fileType;
        $numFileFound = Storage::exists($checkName);

        while($numFileFound){
            // $startNum++;
            $randomStr = Str::random(15);
            $checkName = 'public/' . $column . '/' . implode($boom) . '-' . $randomStr . '.' . $fileType;
            $numFileFound = Storage::exists($checkName);
        }
        $filepath = $file->storeAs('public/' . $column, $checkName);
        // }else{
        //     $filepath = $file->storeAs('public/' . $column, $ogName);
        // }

        // $filepath = $file->store('public/' . $column);
        // $sub = Submission::find($subId);
        Storage::delete(Storage::path($submission[$column]));//should we delete old files?
        $submission[$column] = Storage::url($filepath);

        return response()->json($submission->save(), 200);
    }

    public function restore(Submission $submission, Request $request){
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'team_profile' => ['required', 'string'],
            'character_design' => ['required', 'string'],
            'pilot_video' => ['required', 'string'],
            'story_concept_files' => ['required', 'string'],
            'summary_files' => ['required', 'string'],
            'world_design' => ['required', 'string'],
        ]);

        if(auth()->user()->id != $submission['user_id']){
            return response()->json(['error' => 'Unathorized.'], 401);
        }

        return response()->json($submission->update($validated), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Submission $submission, Request $request)
    {
        $user = auth()->user();
        if($user->hasRole('admin') || $user->id == $submission['user_id']){
            try{
                $submission->delete();
            }catch(\Error $err){
                return response()->json(['error' => $err], 500);
            }
            return self::parseRedirectURL($request['redirect']);
        }else{
            return response()->json(['error' => 'Unathorized.'], 401);
        }
    }

    private static function parseRedirectURL($url){
        if(empty($url)){
            return response()->json([], 200);
        }
        $boom = explode('/', $url);
        for($i = 0; $i < 3; $i++){
            array_shift($boom);
        }
        if(is_numeric(end($boom))){
            $id = array_pop($boom);
            return Redirect::route(implode('.', $boom), $id);
        }else{
            return Redirect::route(implode('.', $boom));
        }
    }
}
