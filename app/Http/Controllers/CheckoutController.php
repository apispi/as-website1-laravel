<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        $agentName   = $request->input('agent');
        $amountCents = (int) round((float) $request->input('amount') * 100);

        $response = Http::asForm()
            ->withToken(config('services.stripe.secret'))
            ->post('https://api.stripe.com/v1/checkout/sessions', [
                'payment_method_types[]'                          => 'card',
                'line_items[0][price_data][currency]'             => 'aud',
                'line_items[0][price_data][product_data][name]'   => $agentName . ' — Monthly Subscription',
                'line_items[0][price_data][unit_amount]'          => $amountCents,
                'line_items[0][quantity]'                         => 1,
                'mode'                                            => 'payment',
                'success_url'                                     => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&agent=' . urlencode($agentName),
                'cancel_url'                                      => route('checkout') . '?agent=' . urlencode($agentName) . '&amount=' . $request->input('amount'),
                'metadata[agent]'                                 => $agentName,
            ]);

        if ($response->failed()) {
            return back()->withErrors(['payment' => 'Could not create checkout session: ' . ($response->json('error.message') ?? 'Unknown error')]);
        }

        return redirect($response->json('url'));
    }

    public function success(Request $request)
    {
        $agentName = $request->query('agent', 'your agent');
        return view('checkout-success', compact('agentName'));
    }
}
