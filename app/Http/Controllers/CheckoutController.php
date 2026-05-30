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
            'agent'    => 'required|string|max:255',
            'agent_id' => 'nullable|integer|exists:agents,id',
            'amount'   => 'required|numeric|min:1',
            'type'     => 'nullable|string|in:training,agent',
        ]);

        $agentName   = $request->input('agent');
        $agentId     = $request->input('agent_id');
        $amountCents = (int) round((float) $request->input('amount') * 100);
        $isTraining  = $request->input('type') === 'training';

        $productName = $isTraining
            ? $agentName
            : $agentName . ' — Monthly Subscription';

        $cancelUrl = route('checkout') . '?agent=' . urlencode($agentName) . '&amount=' . $request->input('amount');
        if ($agentId) {
            $cancelUrl .= '&agent_id=' . $agentId;
        }
        if ($isTraining) {
            $cancelUrl .= '&type=training';
        }

        $metadata = [
            'metadata[agent]' => $agentName,
            'metadata[type]'  => $request->input('type') ?? 'agent',
        ];
        if ($agentId) {
            $metadata['metadata[agent_id]'] = $agentId;
        }

        $response = Http::asForm()
            ->withToken(config('services.stripe.secret'))
            ->post('https://api.stripe.com/v1/checkout/sessions', array_merge([
                'payment_method_types[]'                          => 'card',
                'line_items[0][price_data][currency]'             => 'aud',
                'line_items[0][price_data][product_data][name]'   => $productName,
                'line_items[0][price_data][unit_amount]'          => $amountCents,
                'line_items[0][quantity]'                         => 1,
                'mode'                                            => 'payment',
                'client_reference_id'                             => Auth::id() ?? '',
                'success_url'                                     => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&agent=' . urlencode($agentName),
                'cancel_url'                                      => $cancelUrl,
                'allow_promotion_codes'                           => 'true',
            ], $metadata));

        if ($response->failed()) {
            return back()->withErrors(['payment' => 'Could not create checkout session: ' . ($response->json('error.message') ?? 'Unknown error')]);
        }

        return redirect($response->json('url'));
    }

    public function success(Request $request)
    {
        $agentName = $request->query('agent', 'your agent');
        $sessionId = $request->query('session_id');

        $paymentVerified = false;
        $customerEmail   = null;

        if ($sessionId && config('services.stripe.secret')) {
            $stripe = Http::withToken(config('services.stripe.secret'))
                ->get("https://api.stripe.com/v1/checkout/sessions/{$sessionId}");

            if ($stripe->successful()) {
                $session         = $stripe->json();
                $paymentVerified = ($session['payment_status'] ?? '') === 'paid';
                $customerEmail   = $session['customer_details']['email'] ?? null;
            }
        }

        if ($sessionId && ! $paymentVerified) {
            return redirect()->route('checkout', ['agent' => $agentName])
                ->withErrors(['payment' => 'We could not verify your payment. Please try again or contact support.']);
        }

        ActivityLog::log(
            'purchase.complete',
            "Purchased: {$agentName}",
            Auth::id(),
            null,
            $sessionId ? ['stripe_session_id' => $sessionId] : []
        );

        return view('checkout-success', compact('agentName', 'customerEmail', 'paymentVerified'));
    }
}
