<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
   public function createIntent(Request $request)
{
    $data = $request->validate([
        'bilet_ids'   => 'required|array|min:1',
        'bilet_ids.*' => 'integer|exists:bilets,id',
    ]);
    $totalAmount = \App\Models\Bilet::whereIn('id', $data['bilet_ids'])
        ->sum('fiyat');

    if ($totalAmount <= 0) {
        return response()->json(['message' => 'Geçersiz tutar'], 400);
    }

    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    $intent = \Stripe\PaymentIntent::create([
        'amount' => $totalAmount * 100, 
        'currency' => 'try',
        'payment_method_types' => ['card'],
        'description' => 'Bilet Satışı',
        'metadata' => [
            'bilet_ids' => implode(',', $data['bilet_ids']),
        ]
    ]);

    return response()->json([
        'client_secret' => $intent->client_secret,
    ]);
}
public function handleWebhook(Request $request)
{
    $payload = @file_get_contents('php://input');
    $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
    $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sigHeader, $endpointSecret
        );
    } catch (\UnexpectedValueException $e) {
        return response()->json(['message' => 'Geçersiz payload'], 400);
    } catch (\Stripe\Exception\SignatureVerificationException $e) {
        return response()->json(['message' => 'İmza doğrulama hatası'], 400);
    }

    if ($event->type === 'payment_intent.succeeded') {
        $intent = $event->data->object;

        $biletIds = explode(',', $intent->metadata->bilet_ids ?? '');

        \App\Models\Bilet::whereIn('id', $biletIds)->update([
            'status' => 'odendi',
            'is_active' => true,
        ]);
    }

    if ($event->type === 'payment_intent.payment_failed') {
        $intent = $event->data->object;
        $biletIds = explode(',', $intent->metadata->bilet_ids ?? '');

        \App\Models\Bilet::whereIn('id', $biletIds)->update([
            'status' => 'basarisiz',
        ]);
    }

    return response()->json(['status' => 'success']);
}

}
