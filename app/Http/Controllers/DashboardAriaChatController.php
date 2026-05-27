<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class DashboardAriaChatController extends Controller
{
    private string $systemPrompt = <<<'PROMPT'
You are Aria, the ApiSpi AI assistant. ApiSpi is an AI agents SaaS platform that builds and deploys enterprise-grade autonomous AI agents, digital avatars, and training services for Australian businesses.

## Our AI Agents
- **Bid & Tender Response** — Automates government RFQ/RFT responses, CV matching, and compliance matrices
- **Security & Compliance** — Compliance reporting, gap analysis, policy drafting, vulnerability assessment
- **Enterprise Architecture** — Architecture reviews, technology roadmapping, decision records
- **Digital Avatar** — AI-powered video avatars for professional services (tradies, property agents, lawyers, accountants, beauticians, hotel marketers)
- **Knowledge Management** — Captures, organises, and surfaces organisational knowledge
- **Cyber Incident Response** — Threat detection, incident triage, forensic analysis
- **Content Creator** — AI content generation across formats (articles, video scripts, social)
- **Customer Support Bot** — Intelligent ticket handling, escalation, and automated responses
- **Data Analyzer** — Data extraction, analysis, visualisation, and predictive modelling

## Digital Avatars
ApiSpi's Digital Avatar service creates AI-powered video personas for professional services businesses. Tailored for tradies, property agents, lawyers, accountants, beauticians, and hotel marketers. Avatars handle lead response, client education, and outreach at scale — 24/7. Book a free demo at /digital-avatars.

## Training Courses
- Introduction to AI — Full Day Workshop, $1,500/person (Popular)
- Digital Avatar — Online, $250/avatar (New)
- Prompt Engineering Masterclass — Workshop, $750/person
- AI for Business Leaders — Half Day Workshop, $995/person
- Building AI Agents with APIs — Online 3-day, $1,200/person
- Enterprise AI Strategy — 2-day Workshop, $2,500/person (Certification)

## Partner Program
ApiSpi offers two partnership types for agencies, consultants, and referrers:
- **Referral Partner** — Earn up to 20% recurring revenue share by referring clients. No delivery required.
- **Agency Partner** — For digital/marketing agencies delivering AI solutions to clients. Includes agency-tier pricing, volume discounts, joint go-to-market, and co-sell support.
Partners can apply at /partners. Onboarding takes 48 hours from approval.

## Contact
- Sales & General: sales@apispi.com
- Payments: payment@apispi.com
- Business hours: Mon–Fri 9 AM–6 PM AEST

## How to respond
- Be friendly, concise, and professional
- Keep responses under 150 words unless the user asks for detail
- Use bullet points for lists
- For complex or specific pricing inquiries, encourage using the contact form or emailing sales@apispi.com
- Never invent features, pricing, or details not listed above
- If asked something outside ApiSpi's scope, politely redirect
PROMPT;

    public function send(Request $request)
    {
        $user = Auth::user();
        $key  = 'aria:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 30)) {
            return response()->json(['error' => 'Too many messages. Please wait a moment.'], 429);
        }
        RateLimiter::hit($key, 60);

        $request->validate([
            'message'               => 'required|string|max:2000',
            'history'               => 'nullable|array|max:20',
            'history.*.role'        => 'required|in:user,assistant',
            'history.*.content'     => 'required|string|max:4000',
        ]);

        // Prefer user's own Anthropic connector key, fall back to server key
        $userConnector = $user->userConnectors()
            ->where('status', 'active')
            ->whereHas('connector', fn ($q) => $q->where('slug', 'anthropic'))
            ->first();

        $apiKey = $userConnector?->config['api_key'] ?? config('services.anthropic.key');

        if (empty($apiKey)) {
            return response()->json([
                'reply' => "To use Aria, add your Anthropic API key via **My Connectors → Anthropic**, or contact sales@apispi.com.",
            ]);
        }

        $messages   = collect($request->input('history', []))->takeLast(10)->values()->toArray();
        $messages[] = ['role' => 'user', 'content' => trim($request->input('message'))];

        $response = Http::timeout(30)
            ->withHeaders([
                'x-api-key'         => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])
            ->post('https://api.anthropic.com/v1/messages', [
                'model'      => config('services.anthropic.model', 'claude-sonnet-4-5'),
                'max_tokens' => 512,
                'system'     => $this->systemPrompt,
                'messages'   => $messages,
            ]);

        if ($response->failed()) {
            $errorType = $response->json('error.type');
            if ($errorType === 'authentication_error') {
                return response()->json(['error' => 'Your Anthropic API key is invalid. Please update it in My Connectors → Anthropic.'], 422);
            }
            return response()->json(['error' => 'Something went wrong. Please try again.'], 500);
        }

        return response()->json([
            'reply' => $response->json('content.0.text', 'Sorry, I couldn\'t process that.'),
        ]);
    }
}
