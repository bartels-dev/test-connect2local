<?php

namespace App\Http\Controllers;

use \App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
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
		//return view('tags.add', ['photo' => $photo]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);

		//Check if tag exists in database
		if(false == Tag::where('name', $request->name)->exists() ){
			//If the name doesn't exist then create a new one
			$newTag = new Tag;
			$newTag->name = $request->name;
			$newTag->save();
		};

		//Attach the tag to the entity
		$tag = new Tag;
		$tag->tag_id = Tag::where('name', $request->name)->get()->first()->id;
		$tag->entity_id = $request->entity_id;
		$tag->entity_type = $request->entity_type;
		$media = \strval($request->entity_type)::find($request->entity_id);
		$media->tags()->attach($tag->tag_id);
		//dd($media->tags());

		return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$tag = Tag::find($id);
		$comments_shown = 2;
		//dd($tag->photos);
        return view('tags.show', ['tag' => $tag, 'comments_shown' => $comments_shown]);
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
    }
}
