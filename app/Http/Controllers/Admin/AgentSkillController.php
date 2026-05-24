<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Skill;

class AgentSkillController extends Controller
{
    public function refresh(Agent $agent, Skill $skill)
    {
        $agent->skills()->updateExistingPivot($skill->id, [
            'name'         => $skill->name,
            'description'  => $skill->description,
            'category'     => $skill->category,
            'refreshed_at' => now(),
        ]);

        return back()->with('success', "Skill \"{$skill->name}\" refreshed from catalog.");
    }
}
