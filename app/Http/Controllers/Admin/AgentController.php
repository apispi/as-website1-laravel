<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Connector;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    public function index()
    {
        $agents     = Agent::orderBy('sort_order')->orderBy('name')->get();
        $connectors = Connector::orderBy('sort_order')->orderBy('name')->get();
        $skills     = Skill::orderBy('category')->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.agents.index', compact('agents', 'connectors', 'skills'));
    }

    public function show(Agent $agent)
    {
        $agent->load('skills', 'connectors');
        $subscriptions = $agent->subscriptions()->with('user')->latest('started_at')->get();
        return view('admin.agents.show', compact('agent', 'subscriptions'));
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
        $this->syncSkills($agent, $request->input('skill_ids', []));
        $agent->connectors()->sync($request->input('connector_ids', []));
        return redirect()->route('admin.agents.edit', $agent)->with('success', 'Agent created.')
            ->with('active_tab', $this->safeTab($request));
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
        $this->syncSkills($agent, $request->input('skill_ids', []));
        $agent->connectors()->sync($request->input('connector_ids', []));
        return redirect()->route('admin.agents.edit', $agent)->with('success', 'Agent updated.')
            ->with('active_tab', $this->safeTab($request));
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('admin.agents.index')->with('success', 'Agent deleted.');
    }

    private function syncSkills(Agent $agent, array $newIds): void
    {
        $newIds  = array_map('intval', $newIds);
        $currIds = $agent->skills()->pluck('skills.id')->toArray();

        $toDetach = array_diff($currIds, $newIds);
        $toAttach = array_diff($newIds, $currIds);

        if ($toDetach) {
            $agent->skills()->detach($toDetach);
        }

        if ($toAttach) {
            $skills = Skill::whereIn('id', $toAttach)->get()->keyBy('id');
            foreach ($toAttach as $skillId) {
                $s = $skills[$skillId] ?? null;
                $agent->skills()->attach($skillId, [
                    'name'         => $s?->name ?? '',
                    'description'  => $s?->description ?? '',
                    'category'     => $s?->category ?? '',
                    'refreshed_at' => now(),
                ]);
            }
        }
    }

    private function safeTab(Request $request): string
    {
        return in_array($request->input('active_tab'), ['details', 'skills', 'connectors'])
            ? $request->input('active_tab')
            : 'details';
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
            'checkout_name'        => 'nullable|string|max:255',
            'stripe_payment_link'  => 'nullable|url|max:500',
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
