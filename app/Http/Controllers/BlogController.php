<?php

namespace App\Http\Controllers;

class BlogController extends Controller
{
    private array $validSlugs = [
        'five-agents-service-business-2026',
        'ai-agents-australian-privacy-law',
        'chatbot-to-agent-upgrade',
        'ai-agents-australian-smb-2026',
        'digital-avatars-professional-services',
        'ai-agents-partner-program',
        'claude-4-agentic-leap',
        'mcp-model-context-protocol',
        'agentic-ai-government-procurement',
        'getting-started-with-agents',
        'agent-best-practices',
        'future-of-agentic-ai',
        'building-custom-agents',
        'scaling-agents',
        'monitoring-optimization',
        'real-world-success-stories',
        'api-integration-guide',
        'security-and-compliance',
    ];

    public function index()
    {
        return view('blog.index');
    }

    public function show(string $slug)
    {
        if (! in_array($slug, $this->validSlugs)) {
            abort(404);
        }

        return view("blog.{$slug}");
    }
}
