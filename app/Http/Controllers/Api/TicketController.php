<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Manage tickets for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function purchase(Request $request)
    {
        $user = $request->user(); //getting the current logged in user

        $stripe = config('stripe');
        // Create payment record
        \Stripe\Stripe::setApiKey($stripe['secret-key']);

        try {
            $charge = \Stripe\Charge::create([
                'amount' => $request->amount,
                'currency' => 'usd',
                'customer' => $user->ptoken,
            ]);
        } catch(Exception $e){
            report($e);
            return response()->json(['result' => 'failed', 'message' => 'Transaction failed.'], 200);
        }

        // Send confirmation email
        /*
        Mail::to($user->email)->send(
            new \App\Mail\purchaseReceipt($user)
        );
        */

        return response()->json(['result' => 'success', 'message' => 'Purchase successful!'], 200);
    }

    public function transaction(Request $request)
    {
        //dd($request);
        $user_id = Auth::user()->id;
        //Check to see if User has enough tickets
        If(Auth::user()->ticket_balance < $request->amount) {
            //If not send to purchase tickets page
            return view('static.tickets');
        }

        //Check if Reason is acceptable
        If($request->reason !== 'tip') {
            //Return back with an error message!
            return back();
        }

        //
        //Error handling
        //Check for valid model_id, user_id, transaction_id

        DB::table('ticket_transactions')->insert([
            'user_id' => $user_id,
            'model_id' => $request->model_id,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'redeemed' => false,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);
        DB::table('users')->where('id', $user_id)->decrement('ticket_balance', $request->amount);
        //Check if the Transaction was a tip to a Model
        If($request->reason == 'tip') {
            DB::table('users')->where('id', $request->model_id)->increment('tickets_recieved', $request->amount);
        }

        return back();
    }


}
