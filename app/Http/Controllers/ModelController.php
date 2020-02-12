<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;

class ModelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		/*
		$models = User::all();
		for($m=0; $m<count($models); $m++){
			if(!$models[$m]->hasRole('model')) unset($models[$m]);
		}
		*/
		$role = Role::with('users')->where('slug', 'model')->first();
		$models = $role->users->sortByDesc('updated_at');
        return view('models.index', ['models' => $models]);
    }

}
