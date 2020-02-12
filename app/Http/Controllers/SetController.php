<?php

namespace App\Http\Controllers;

use \App\Set;
use \App\SetPhoto;

use Illuminate\Http\Request;
//use Illuminate\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Image;

class SetController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

	public function getSetCover($id)
	{
		$set = Set::find($id);
		$path = storage_path() . '/' . $set->filename;

		if(!File::exists($path)) abort(404);

		$file = File::get($path);
		$type = File::mimeType($path);

		$response = Response::make($file, 200);
		$response->header("Content-Type", $type);

		return $response;
	}
	public function getSetPhoto($id)
	{
		$setPhoto = SetPhoto::find($id);

		$path = storage_path() . '/' . $setPhoto->filename;

		if(!File::exists($path)) abort(404);
		$file = File::get($path);

		$type = File::mimeType($path);
		$response = Response::make($file, 200);
		$response->header("Content-Type", $type);

		//dd($setPhoto);
		return $response;
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$sets = Set::orderBy('created_at','desc')->get();
        $comments_shown = 2;

        return view('sets.index', ['sets' => $sets, 'comments_shown' => $comments_shown]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sets.upload');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$this->validate($request, ['title' => 'required', 'photos' => 'required']);
		$coverPhoto = $request->file('cover_photo');
		$coverPath = $coverPhoto->store('media/sets', 'local');

		$set = new Set;
		$id = $request->user()->id;
		$set->user_id = $id;
		$set->title = $request->title;
		$set->caption = $request->caption;
		$set->filename = 'app/'.$coverPath;
		$set->mime_type = Input::file('cover_photo')->getClientMimeType();
		$set->save();


		$files = $request->file('photos');

		foreach($files as $file){
			$path = $file->store('media/sets', 'local');

			$setPhoto = new SetPhoto;
			$setPhoto->set_id = $set->id;
			$setPhoto->user_id = $id;
			$setPhoto->filename = 'app/'.$path;
			$setPhoto->mime_type = 'image/*';
			$set->setPhotos()->save($setPhoto);
			$comments_shown = 10000;
		}
		//dd($set);
		return view('sets.show', ['set' => $set, 'comments_shown' => $comments_shown]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $set = Set::find($id);
        $comments_shown = 10000;

        return view('sets.show', ['set' => $set, 'comments_shown' => $comments_shown]);
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
