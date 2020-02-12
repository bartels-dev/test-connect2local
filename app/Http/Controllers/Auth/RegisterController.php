<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

 	public function index($accttype){
		return view ('auth.register')->with('accttype', $accttype);
	}

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'unique:users,uname'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
/*         $stripe = config('stripe');
        // Create payment record
        \Stripe\Stripe::setApiKey($stripe['secret-key']);
        // Create a Customer:
        $customer = \Stripe\Customer::create([
            'source' => $data['ptoken'],
            'email' => $data['email'],
        ]); */

/*         // Charge the initial month's membership:
        $charge = \Stripe\Charge::create([
            'amount' => $stripe['member'],
            'currency' => 'usd',
            'customer' => $customer->id,
        ]); */

        //dd($data['user_role']);
        // Create the user record
        $user = new User;
        $user->name = $data['name'];
        $user->uname = $data['user_name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        if(isset($data['bio'])){
            $user->bio = $data['bio'];
        }
        $user->save();
        //dd($user);
        $user->roles()->attach(intval($data['user_role']));

        // Send confirmation email
        Mail::to($user->email)->send(
            new \App\Mail\newMember($user)
        );

        return($user);
    }
}





