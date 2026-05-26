<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'type'   => 'nullable|string|in:training,agent',
        ]);

        $agentName   = $request->input('agent');
        $amountCents = (int) round((float) $request->input('amount') * 100);
        $isTraining  = $request->input('type') === 'training';

        $productName = $isTraining
            ? $agentName
            : $agentName . ' — Monthly Subscription';

        $cancelUrl = route('checkout') . '?agent=' . urlencode($agentName) . '&amount=' . $request->input('amount');
        if ($isTraining) {
            $cancelUrl .= '&type=training';
        }

        $response = Http::asForm()
            ->withToken(config('services.stripe.secret'))
            ->post('https://api.stripe.com/v1/checkout/sessions', [
                'payment_method_types[]'                          => 'card',
                'line_items[0][price_data][currency]'             => 'aud',
                'line_items[0][price_data][product_data][name]'   => $productName,
                'line_items[0][price_data][unit_amount]'          => $amountCents,
                'line_items[0][quantity]'                         => 1,
                'mode'                                            => 'payment',
                'success_url'                                     => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&agent=' . urlencode($agentName),
                'cancel_url'                                      => $cancelUrl,
                'metadata[agent]'                                 => $agentName,
                'allow_promotion_codes'                           => 'true',
            ]);

        if ($response->failed()) {
            return back()->withErrors(['payment' => 'Could not create checkout session: ' . ($response->json('error.message') ?? 'Unknown error')]);
        }

        return redirect($response->json('url'));
    }

    public function success(Request $request)
    {
        $agentName = $request->query('agent', 'your agent');
        $sessionId = $request->query('session_id');

        ActivityLog::log(
            'purchase.complete',
            "Purchased: {$agentName}",
            Auth::id(),
            null,
            $sessionId ? ['stripe_session_id' => $sessionId] : []
        );

        return view('checkout-success', compact('agentName'));
    }
}
