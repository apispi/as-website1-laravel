<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class ChatController extends Controller
{
    private string $systemPrompt = <<<'PROMPT'
You are Aria, the ApiSpi AI assistant. ApiSpi is an AI agents SaaS platform that builds and deploys enterprise-grade autonomous AI agents and training services.

## Our AI Agents
- **Bid & Tender Response** — Automates government RFQ/RFT responses, CV matching, and compliance matrices
- **Security & Compliance** — Compliance reporting, gap analysis, policy drafting, vulnerability assessment
- **Enterprise Architecture** — Architecture reviews, technology roadmapping, decision records
- **Digital Avatar** — AI-powered video avatars for content creation and customer engagement
- **Knowledge Management** — Captures, organises, and surfaces organisational knowledge
- **Cyber Incident Response** — Threat detection, incident triage, forensic analysis
- **Content Creator** — AI content generation across formats (articles, video scripts, social)
- **Customer Support Bot** — Intelligent ticket handling, escalation, and automated responses
- **Data Analyzer** — Data extraction, analysis, visualisation, and predictive modelling

## Training Courses
- Introduction to AI — Full Day Workshop, $1,500/person (Popular)
- Digital Avatar — Online, $250/avatar (New)
- Prompt Engineering Masterclass — Workshop, $750/person
- AI for Business Leaders — Half Day Workshop, $995/person
- Building AI Agents with APIs — Online 3-day, $1,200/person
- Enterprise AI Strategy — 2-day Workshop, $2,500/person (Certification)

## Contact
- Sales & General: sales@apispi.com
- Payments: payment@apispi.com
- Business hours: Mon–Fri 9 AM–6 PM AEST

## How to respond
- Be friendly, concise, and professional
- Keep responses under 120 words
- Use bullet points for lists
- For complex or specific pricing inquiries, encourage using the contact form or emailing sales@apispi.com
- Never invent features, pricing, or details not listed above
- If asked something outside ApiSpi's scope, politely redirect
PROMPT;

    public function send(Request $request)
    {
        $key = 'chat:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 30)) {
            return response()->json(['error' => 'Too many messages. Please wait a moment.'], 429);
        }
        RateLimiter::hit($key, 60);

        $request->validate([
            'message'   => 'required|string|max:1000',
            'history'   => 'nullable|array|max:20',
            'history.*.role'    => 'required|in:user,assistant',
            'history.*.content' => 'required|string|max:2000',
        ]);

        $messages = collect($request->input('history', []))
            ->takeLast(10)
            ->values()
            ->toArray();

        $messages[] = ['role' => 'user', 'content' => trim($request->input('message'))];

        $apiKey = config('services.anthropic.key');

        if (empty($apiKey)) {
            return response()->json([
                'reply' => "Hi! I'm Aria, ApiSpi's assistant. Our API key isn't configured yet — please email **sales@apispi.com** or use the contact form and we'll get back to you shortly.",
            ]);
        }

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
            return response()->json(['error' => 'Something went wrong. Please try the contact form below.'], 500);
        }

        $reply = $response->json('content.0.text', 'Sorry, I couldn\'t process that. Please use the contact form.');

        return response()->json(['reply' => $reply]);
    }
}
