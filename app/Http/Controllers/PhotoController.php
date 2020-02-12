<?php

namespace App\Http\Controllers;

use App\Photo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Image;

class PhotoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function getPhoto($id)
	{
		$photo = Photo::find($id);
		$path = storage_path() . '/' . $photo->filename;

		if(!File::exists($path)) abort(404);

		$file = File::get($path);
		$type = File::mimeType($path);

		$response = Response::make($file, 200);
		$response->header("Content-Type", $type);

		return $response;
	}
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		$photos = Photo::orderBy('created_at','desc')->get();
		//dd($photos[4]->likes->contains('user_id', Auth::user()->id));
		//dd($photos[3]->comments[0]->replies[0]->replies);
        $comments_shown = 2;
		return view('photos.index', ['photos' => $photos, 'comments_shown' => $comments_shown]);
    }

    public function create()
    {
        return view('uploads');
        //return view('photos.upload');
    }

    public function store(Request $request)
    {
		//dd($request->file('file')[0]);
		//dd($request->file('photo'));

		//OLD photo upload validation
		/*$validationData = $request->validate([
			'photo' => 'mimes:jpeg,png,jpg,gif|max:2048'
		]);
		*/

		$user = $request->user()->name;
		//$path = $request->file('photo')->store('media/photos', 'local');    //Old path with no dropzone uploading
		$path = $request->file('photo')->store('media/photos', 'local');

		//$originalPhoto = Image::make(Input::file('photo'));
		$originalPhoto = Image::make(Input::file('photo'));
		$height = $originalPhoto->height();
		//$originalPhoto->store($path);

		$photo = new Photo();
		$photo->user_id = $request->user()->id;
		$photo->filename = 'app/'.$path;
		//$photo->mime_type = Input::file('photo')->getClientMimeType();
		$photo->mime_type = Input::file('photo')->getClientMimeType();
		$photo->title = $request->title;
		$photo->caption = $request->caption;
		$photo->save();

        //return response()->json(['id' => $photo->id], 200);

    }

    public function show($id)
    {
		$photo = Photo::find($id);
        $comments_shown = 10000;

        return view('photos.show', ['photo' => $photo, 'comments_shown' => $comments_shown]);
    }
    public function edit()
    {
		//
    }
    public function update()
    {
        //
    }
    public function destroy()
    {
        //
    }
}
