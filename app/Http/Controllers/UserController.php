<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // List all users
		$users = App\User::all();
        $roles = App\Role::all()->keyBy('id');
        return view('users.users', ['users' => $users, 'roles' => $roles]);
    }

    public function userSearch(Request $request){
        $roles = App\Role::all()->keyBy('id');
		    $users = App\User::where('name', 'like', '%' . $request->search . '%')
			     ->orWhere('email', 'like', '%' . $request->search . '%')
			     ->get();
        return view('users.users', ['users' => $users, 'sites' => $sites, 'roles' => $roles]);
	}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // New users created during register process.  This will only be used by admin //
        if(App\User::where('email', $request->username)->exists()){
            return response()->json(['result' => 'error', 'message' => 'Email address already exists.'], 200);
        }
        $user = new App\User;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->status = $request->status;
        $user->ptoken = $request->ptoken;
        $user->save();

        $user->roles()->attach($request->role);
        // Send confirmation email
        Mail::to($user->email)->send(
            new App\Mail\newMember()
        );

        return response()->json(['result' => 'success', 'message' => 'User Created!'], 200);
    }

    public function edit(Request $request){
		//
		//Make sure User has permission to edit this profile
		//
        $user = $request->user(); //getting the current logged in user
        //$view = ($user->hasRole('model') ? 'model.edit_profile' : 'user.edit_profile');
		$view = 'user.edit_profile';
        return view($view, ['profileUser' => $user]);
    }

    public function viewProfile(Request $request){
      //

      $user = Auth::user(); //getting the current logged in user
      //dd($user);
	  //Get the username of the requested accout
	  //If it is not supplied, set username as the logged in user.
	  if(isset($request->username)){
		$owner = App\User::where('uname', $request->username)->first();
	  }else{
		  $owner = $user;
	  }
	  $photos = $owner->photos;
	  $sets = $owner->sets;
	  $videos = $owner->videos;
	  $comments_shown = 5;
	  //dd($owner->followers->contains('id', Auth::user()->id));
      return view('user.profile', ['owner' => $owner, 'user' => $user, 'photos' => $photos, 'sets' => $sets, 'videos' => $videos, 'comments_shown' => $comments_shown]);
    }

    public function viewTimeline(Request $request)
    {
        //

        //dd(Auth::user()->followers[0]->photos->sortByDesc('created_at')->first());

        //Find all the Current User's Followers
        $followers = Auth::user()->followers;
        $follower_posts = []; //Create array of all your followers latest posts

        foreach($followers as $follower){
            $photo = $follower->photos->sortByDesc('created_at')->first();
            $follower_posts[] = $photo;
        }

        $comments_shown = 2;

        return view('user.timeline', ['posts' => $follower_posts, 'comments_shown' => $comments_shown]);
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
        $user = App\User::find($id);
        if(!$user) return response()->json(['result' => 'error', 'message' => 'User does not exist!'], 200);
        $message = '';
        if(App\User::where('uname', $request->uname)->where('id', '!=', $id)->exists()){
            $message = 'Screen name already used.  Please choose a different name.';
        }
        if(App\User::where('email', $request->email)->where('id', '!=', $id)->exists()){
            $message = 'Email address already exists.';
        }
        if(!empty($message)){
            return response()->json(['result' => 'error', 'message' => $message], 200);
        }
		if(!empty($request->password)){
			$user->password = bcrypt($request->password);
		}
        $user->uname = $request->uname;
        $user->bio = $request->bio;

        $user->save();
		$photos = App\Photo::where('user_id', $user->id)->get();

		return redirect()->action('UserController@viewProfile', ['username' => $request->uname]);
		//return view('user.profile', ['profileUser' => $user, 'user' => $user, 'photos' => $photos]);
        //return response()->json(['result' => 'success', 'message' => 'User succesfully updated!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request){
      $user = App\User::where('id', $request->id)->first();
      $user->delete();
      return response()->json(['message' => 'User successfully deleted!'], 200);
    }

    public function follow(Request $request)
    {
        //Current User wants to follow someone
        //dd($request);
        Auth::user()->following()->attach($request->id);
    }

    public function unfollow(Request $request)
    {
        //Current User wants to unfollow someone
        //dd($request);
        Auth::user()->following()->detach($request->id);
    }

}
