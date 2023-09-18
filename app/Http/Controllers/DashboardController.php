<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class DashboardController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminDashboard()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Admin');
        })->get();
        return view('admin.index',compact('users','role'));
    }

    public function refundPayment(Request $request){
        $payment_id = $request->stripe_id;
        $contoller = new ProductController();
        $call = $contoller->refund($payment_id);
        redirect(route('buy'));
    }
}
