<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use App\Http\Controllers\ProductController;

class B2CDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware(['auth','user-access']);
    // }

    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        $users = User::role($role)->get();
        return view('B2C.index',compact('users','role'));
    }

    public function refundPayment(Request $request){
        //dd($request->all());
        $payment_id = $request->stripe_id;
        $contoller = new ProductController();
        $call = $contoller->refund($payment_id);
        redirect(route('buy'));
    }
}
