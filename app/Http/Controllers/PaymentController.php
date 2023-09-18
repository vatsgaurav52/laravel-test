<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    public function purchase(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $charge = Charge::create([
                'amount' => $amount, // Amount in cents
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Product Purchase',
            ]);

            // Handle successful payment and role assignment here
        } catch (\Exception $e) {
            dd($e);
        }

        // Redirect or display a confirmation page
    }
}
