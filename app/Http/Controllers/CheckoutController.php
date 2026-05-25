<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function show()
    {
        return view('checkout');
    }

    public function createSession(Request $request)
    {
        $request->validate([
            'agent'  => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
        ]);

        $agentName = $request->input('agent');
        $amountCents = (int) round((float) $request->input('amount') * 100);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'aud',
                    'product_data' => ['name' => $agentName . ' — Monthly Subscription'],
                    'unit_amount'  => $amountCents,
                ],
                'quantity' => 1,
            ]],
            'mode'        => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&agent=' . urlencode($agentName),
            'cancel_url'  => route('checkout') . '?agent=' . urlencode($agentName) . '&amount=' . $request->input('amount'),
            'metadata'    => ['agent' => $agentName],
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $agentName = $request->query('agent', 'your agent');
        return view('checkout-success', compact('agentName'));
    }
}
