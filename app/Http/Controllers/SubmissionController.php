<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        return response()->json(Submission::create($validated), 200);
    }

    public function storeSubmissionFile(Request $request)
    {
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
        return Inertia::render('Submission', [
            'submission' => $submission
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

        return response()->json($submission->update($validated), 200);
    }

    public function updateSubmissionFile(Request $request, Submission $submission){
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Submission $submission)
    {
        //
    }
}
