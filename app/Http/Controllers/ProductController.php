<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseConfirmationMail;


class ProductController extends Controller
{
    public function purchase(Request $request)
    {
        $productType = $request->input('product_type');
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => 1000, // Amount in cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
            ]);

            $paymentIntent->confirm([
                'payment_method' => 'pm_card_'.$request->brand, 
                'return_url' => url('home'), 
            ]);

            $user = Auth::user();
            if ($user) {
                $user->assignRole($productType.' Customer');
                $id = $user->id;
                $user = User::find($id);
                $user->card = $request->lastFourDigits;
                $user->stripe_id  = $paymentIntent->id;
                $user->save();

                $data = ['user'=>$user,'productType'=> $productType];
                Mail::to($user->email)->send(new PurchaseConfirmationMail($data));
            } 
            
            return redirect()->route($productType.'.dashboard')->with('success', 'Product purchased successfully.');
        } catch (\Exception $e) {
            // Handle payment failure
            return back()->with('error', 'Payment failed. Please try again.');
        }
    }

    public function refund($paymentIntentId){
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
        
            // Create a refund for a PaymentIntent
            $refund = \Stripe\Refund::create([
                'payment_intent' => $paymentIntentId,
                'amount' => 1000, // Specify the amount in cents (e.g., $10.00)
            ]);

            $user = Auth::user();
            if ($user) {
                $id = $user->id;
                $user = User::find($id);
                $user->card = '';
                $user->stripe_id  = '';
                $user->save();

                $data = ['user'=>$user];
                Mail::to($user->email)->send(new PurchasedCancelMail($data));
            } 
            
            //Mail::to($user->email)->send(new PurchaseConfirmationMail($data));
            // The $refund object will contain information about the refund if successful
            // You can check $refund->status to see if it was successful ('succeeded')
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Handle any errors related to the request
            // You can check $e->getMessage() for details
            return back()->with('error', 'Payment failed. Please try again.');
        }
        
        

    }
}
