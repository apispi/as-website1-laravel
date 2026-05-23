<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Connector;
use App\Models\Skill;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index()
    {
        $agents     = Agent::orderBy('sort_order')->orderBy('name')->get();
        $connectors = Connector::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.agents.index', compact('agents', 'connectors'));
    }

    public function create()
    {
        $skills     = Skill::orderBy('category')->orderBy('sort_order')->get();
        $connectors = Connector::where('is_active', true)->orderBy('category')->orderBy('name')->get();
        return view('admin.agents.form', compact('skills', 'connectors'));
    }

    public function store(Request $request)
    {
        $data  = $this->validated($request);
        $agent = Agent::create($data);
        $agent->skills()->sync($request->input('skill_ids', []));
        $agent->connectors()->sync($request->input('connector_ids', []));
        return redirect()->route('admin.agents.index')->with('success', 'Agent created.');
    }

    public function edit(Agent $agent)
    {
        $skills     = Skill::orderBy('category')->orderBy('sort_order')->get();
        $connectors = Connector::where('is_active', true)->orderBy('category')->orderBy('name')->get();
        $agent->load('skills', 'connectors');
        return view('admin.agents.form', compact('agent', 'skills', 'connectors'));
    }

    public function update(Request $request, Agent $agent)
    {
        $data = $this->validated($request);
        $agent->update($data);
        $agent->skills()->sync($request->input('skill_ids', []));
        $agent->connectors()->sync($request->input('connector_ids', []));
        return redirect()->route('admin.agents.index')->with('success', 'Agent updated.');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('admin.agents.index')->with('success', 'Agent deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'slug'           => 'required|string|max:100',
            'name'           => 'required|string|max:255',
            'description'    => 'required|string',
            'badge'          => 'nullable|in:Popular,Premium,New',
            'rating'         => 'nullable|numeric|min:0|max:5',
            'users_count'    => 'nullable|string|max:50',
            'price'          => 'nullable|string|max:50',
            'category'       => 'nullable|string|max:100',
            'is_featured'    => 'boolean',
            'is_active'      => 'boolean',
            'sort_order'     => 'integer|min:0',
            'checkout_name'  => 'nullable|string|max:255',
            'target_audience'=> 'nullable|string|max:255',
            'tagline'        => 'nullable|string|max:255',
            'cta_headline'   => 'nullable|string|max:255',
            'cta_sub'        => 'nullable|string|max:255',
        ]) + ['is_featured' => false, 'is_active' => false];

        foreach (['features', 'includes'] as $field) {
            $raw = $request->input($field, '');
            $data[$field] = array_values(array_filter(array_map('trim', explode("\n", $raw))));
        }

        foreach (['use_cases', 'pricing_plans', 'faqs'] as $field) {
            $raw = trim($request->input($field, ''));
            $data[$field] = $raw ? (json_decode($raw, true) ?? []) : [];
        }

        return $data;
    }
}
