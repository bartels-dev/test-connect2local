<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class CommentController extends Controller
{
	public function __construct()
	{
		return $this->middleware('auth');
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
		//dd($request);
        $comment = new Comment();
		$comment->user_id = $request->user()->id;
		$comment->entity_id = $request->entity_id;
		$comment->body = $request->body;
		$media = \strval($request->entity_type)::find($request->entity_id);
		$media->comments()->save($comment);
		$user = Auth::user();
		$user_avatar = $user->avatar();
		$ajax_data = [$user, $user_avatar, $comment];

		return $ajax_data;
    }
	public function replyStore(Request $request)
	{
        //dd($request);
		$reply = new Comment();
		$reply->user_id = $request->user()->id;
		$reply->entity_id = $request->entity_id;
		$reply->body = $request->body;
		$reply->parent_id = $request->parent_id;
		$media = \strval($request->entity_type)::find($request->entity_id);
		$media->comments()->save($reply);

        $user = Auth::user();
        $user_avatar = $user->avatar();
        $ajax_data = [$user, $user_avatar, $reply];

        return $ajax_data;
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
