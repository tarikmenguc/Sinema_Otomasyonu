<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function createIntent(Request $request)
{
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    $intent = \Stripe\PaymentIntent::create([
        'amount' => 5000,
        'currency' => 'try',
        'payment_method_types' => ['card'],
        'description' => 'Bilet Satışı',
    ]);

    return response()->json([
        'client_secret' => $intent->client_secret,
    ]);
}
}
