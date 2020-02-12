<?php

namespace App\Http\Controllers;

use \App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class VideoController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getVideo($id)
	{
		$video = Video::find($id);
		$path = storage_path() . '/' . $video->filename;

		if(!File::exists($path)) abort(404);

		$file = File::get($path);
		$type = File::mimeType($path);

		$response = Response::make($file, 200);
		$response->header("Content-Type", $type);

		return $response;
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$videos = Video::orderBy('created_at','desc')->get();
        $comments_shown = 2;

        return view('videos.index', ['videos' => $videos, 'comments_shown' => $comments_shown]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('videos.upload');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->file('file')[0]);
        /*OLD VIDEO VALIDATION
		$validationData = $request->validate([
			'video' => 'mimes:mp4,flv,ts,mov,wmv,avi'
		]);
        */

        $user = $request->user()->name;
        //$path = $request->file('video')->store('media/videos', 'local');  //Old upload name
        $path = $request->file('video')->store('media/videos', 'local');


        $video = new Video;
        $video->user_id = $request->user()->id;
        $video->title = $request->title;
        $video->caption = $request->caption;
        $video->filename = 'app/'.$path;
        //$video->mime_type = Input::file('video')->getClientMimeType();    //Old upload name
        $video->mime_type = Input::file('video')->getClientMimeType();

        $video->save();
        //$comments_shown = 10000;

        //$video = Video::find($video->id);
        //return view('videos.show', ['video' => $video, 'comments_shown' => $comments_shown]);
        return response()->json(['id' => $video->id], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $video = Video::find($id);
        $comments_shown = 10000;

        return view('videos.show', ['video' => $video, 'comments_shown' => $comments_shown]);
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
