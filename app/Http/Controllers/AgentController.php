<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

class AgentController extends Controller
{
    private array $validSlugs = [
        'bid-tender',
        'security-compliance',
        'enterprise-architecture',
        'digital-avatar',
        'knowledge-management',
        'cyber-incident',
        'content-creator',
        'support-bot',
        'data-analyzer',
    ];

    public function index()
    {
        return view('agents.index');
    }

    public function show(string $slug)
    {
        if (! in_array($slug, $this->validSlugs)) {
            abort(404);
        }

        return view("agents.{$slug}");
    }
}
