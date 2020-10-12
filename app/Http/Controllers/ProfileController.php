<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Auth;

class ProfileController extends Controller
{
    public $user; 

    public function __construct()
    {
        $this->user = Auth::user(); 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if($this->user->hasRoles(['admin', 'test']))
            return Profile::paginate(30);
        else 
            return \App::abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if(!$this->user->hasRoles(['admin'])) \App::abort(403);
        return view('profiles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if(!$this->user->hasRoles(['admin'])) \App::abort(403);
        $tags_id = request()->tags; 
        $skills_id = request()->skills; 

        $profile = Profile::create(request()->all());

        // Attach records of skills and tags to the profile 
        $profile->tags->sync($tags_id);
        $profile->skills->sync($skills_id);

        return response()->json(['profile' => $profile->load(['tags','skills']), 'message'=>'Created successfully!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if(!$this->user->hasRoles(['admin'])) \App::abort(403);
        $profile = Profile::findOrFail($id); 
        return response()->json($profile, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        if(!$this->user->hasRoles(['admin'])) \App::abort(403);
        $profile = Profile::findOrFail($id); 
        return view('profiles.edit', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if(!$this->user->hasRoles(['admin'])) \App::abort(403);
        $profile = Profile::findOrFail($id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if(!$this->user->hasRoles(['admin'])) \App::abort(403);
        $profile = Profile::FindOrFail($id); 
        $profile->delete();
        return response()->json(['message' => 'Profile Deleted Successfully']);
    }

    /**
     * Get the changes done on a model
     */
    public function getChanges($id){
        $profile = Profile::find($id); 
        return $profile->historyChanges();
    }
}
