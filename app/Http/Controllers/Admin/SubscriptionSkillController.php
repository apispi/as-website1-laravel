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
        $subscription->load(['user', 'agent.skills']);

        $subSkill = $subscription->skills()->where('skills.id', $skill->id)->first();
        abort_unless($subSkill, 404);

        // Agent's customized definition; falls back to catalog in the view if absent
        $agentDefault = $subscription->agent?->skills->firstWhere('id', $skill->id);

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

    public function populate(Subscription $subscription)
    {
        $subscription->load('agent.skills', 'skills');

        $existingIds = $subscription->skills->pluck('id')->all();
        $added = 0;

        foreach ($subscription->agent->skills as $skill) {
            if (!in_array($skill->id, $existingIds)) {
                $subscription->skills()->attach($skill->id, [
                    'name'         => $skill->pivot->name ?: $skill->name,
                    'description'  => $skill->pivot->description ?: $skill->description,
                    'category'     => $skill->pivot->category ?: $skill->category,
                    'refreshed_at' => now(),
                ]);
                $added++;
            }
        }

        $msg = $added > 0
            ? "{$added} skill" . ($added > 1 ? 's' : '') . " added from agent defaults."
            : "All agent skills are already present — nothing to add.";

        return redirect()
            ->route('admin.subscriptions.show', $subscription)
            ->with('success', $msg);
    }

    public function refresh(Subscription $subscription, Skill $skill)
    {
        $subscription->load('agent.skills');
        $agentDefault = $subscription->agent?->skills->firstWhere('id', $skill->id);

        DB::table('subscription_skill')
            ->where('subscription_id', $subscription->id)
            ->where('skill_id', $skill->id)
            ->update([
                'name'         => ($agentDefault?->pivot->name) ?: $skill->name,
                'description'  => ($agentDefault?->pivot->description) ?: $skill->description,
                'category'     => ($agentDefault?->pivot->category) ?: $skill->category,
                'refreshed_at' => now(),
            ]);

        return redirect()
            ->route('admin.subscriptions.skills.show', [$subscription, $skill])
            ->with('success', "Skill \"{$skill->name}\" refreshed from agent default.");
    }
}
