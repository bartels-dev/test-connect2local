<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAvatarController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function store(Request $request){
        $request->validate([
            'avatar' => ['required', 'image']
        ]);

        auth()->user()->update([
            'avatar' => request()->file('avatar')->store('avatars', 'public')
        ]);

        return back();
    }
}
