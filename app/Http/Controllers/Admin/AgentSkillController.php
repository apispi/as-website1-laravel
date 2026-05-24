<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Skill;

class AgentSkillController extends Controller
{
    public function show(Agent $agent, Skill $skill)
    {
        $agentSkill = $agent->skills()->where('skills.id', $skill->id)->first();

        abort_unless($agentSkill, 404);

        return view('admin.agent-skill', compact('agent', 'skill', 'agentSkill'));
    }

    public function update(\Illuminate\Http\Request $request, Agent $agent, Skill $skill)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        \DB::table('agent_skill')
            ->where('agent_id', $agent->id)
            ->where('skill_id', $skill->id)
            ->update($data);

        return redirect()
            ->route('admin.agents.skills.show', [$agent, $skill])
            ->with('success', 'Skill definition updated.');
    }

    public function refresh(Agent $agent, Skill $skill)
    {
        \DB::table('agent_skill')
            ->where('agent_id', $agent->id)
            ->where('skill_id', $skill->id)
            ->update([
                'name'         => $skill->name,
                'description'  => $skill->description,
                'category'     => $skill->category,
                'refreshed_at' => now(),
            ]);

        return redirect()
            ->route('admin.agents.skills.show', [$agent, $skill])
            ->with('success', "Skill \"{$skill->name}\" refreshed from catalog.");
    }
}
