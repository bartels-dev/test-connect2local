<?php

namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class LikeController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function like(Request $request)
    {
        $user_id = Auth::user()->id;
        //dd($user_id);
        $like = new Like;
		$like->user_id = $user_id;
		$like->entity_id = $request->entity_id;
		$media = \strval($request->entity_type)::find($request->entity_id);
		$media->likes()->save($like);

		//return back();
    }

    public function unlike(Request $request)
    {
        $user_id = Auth::user()->id;
        $record = Like::where('user_id', $user_id)->where('entity_id', $request->entity_id)->where('entity_type', $request->entity_type)->delete();
        //dd($record);
    }
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
		dd($request);
        $like = new Like;
		$like->user_id = $request->user()->id;
		$like->entity_id = $request->entity_id;
		$media = \strval($request->entity_type)::find($request->entity_id);
		$media->likes()->save($like);

		//return back();
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
    }


}
