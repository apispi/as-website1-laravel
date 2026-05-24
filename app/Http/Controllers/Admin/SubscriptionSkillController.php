<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionSkillController extends Controller
{
    public function show(Subscription $subscription, Skill $skill)
    {
        $subSkill = $subscription->skills()->where('skills.id', $skill->id)->first();
        abort_unless($subSkill, 404);

        $agentDefault = $subscription->agent->skills()->where('skills.id', $skill->id)->first();

        return view('admin.subscription-skill', compact('subscription', 'skill', 'subSkill', 'agentDefault'));
    }

    public function update(Request $request, Subscription $subscription, Skill $skill)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        DB::table('subscription_skill')
            ->where('subscription_id', $subscription->id)
            ->where('skill_id', $skill->id)
            ->update($data);

        return redirect()
            ->route('admin.subscriptions.skills.show', [$subscription, $skill])
            ->with('success', 'Skill definition updated.');
    }

    public function refresh(Subscription $subscription, Skill $skill)
    {
        $agentDefault = $subscription->agent->skills()->where('skills.id', $skill->id)->first();
        abort_unless($agentDefault, 404);

        DB::table('subscription_skill')
            ->where('subscription_id', $subscription->id)
            ->where('skill_id', $skill->id)
            ->update([
                'name'         => $agentDefault->pivot->name ?: $skill->name,
                'description'  => $agentDefault->pivot->description ?: $skill->description,
                'category'     => $agentDefault->pivot->category ?: $skill->category,
                'refreshed_at' => now(),
            ]);

        return redirect()
            ->route('admin.subscriptions.skills.show', [$subscription, $skill])
            ->with('success', "Skill \"{$skill->name}\" refreshed from agent default.");
    }
}
