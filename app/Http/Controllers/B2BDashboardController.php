<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class B2BDashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        $users = User::role($role)->get();
        return view('B2B.index',compact('users','role'));
    }

    public function refundPayment(Request $request){
        $payment_id = $request->stripe_id;
        $contoller = new ProductController();
        $call = $contoller->refund($payment_id);
        redirect(route('buy'));
    }
}
