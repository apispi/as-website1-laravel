<?php

namespace App\Http\Controllers;

use App\Models\Agent;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Agent::active()->get();
        return view('agents.index', compact('agents'));
    }

    public function show(string $slug)
    {
        $agent = Agent::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('agents.show', compact('agent'));
    }
}
