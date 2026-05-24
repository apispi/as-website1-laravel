@extends('layouts.admin')

@section('title', ($subSkill->pivot->name ?: $skill->name) . ' — ' . ($subscription->user?->name ?? 'User') . ' - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="subscription-skill-detail"
    data-props="{{ json_encode([
        'user'      => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken' => csrf_token(),
        'flashSuccess' => session('success', ''),
        'subscription' => [
            'id'     => $subscription->id,
            'status' => $subscription->status,
        ],
        'subUser' => $subscription->user ? [
            'id'   => $subscription->user->id,
            'name' => $subscription->user->name,
        ] : null,
        'agent' => $subscription->agent ? [
            'id'   => $subscription->agent->id,
            'name' => $subscription->agent->name,
        ] : null,
        'skill' => [
            'id'           => $skill->id,
            'slug'         => $skill->slug,
            'catalog_name' => $skill->name,
            'catalog_desc' => $skill->description,
            'catalog_category' => $skill->category,
        ],
        'pivot' => [
            'name'         => $subSkill->pivot->name,
            'description'  => $subSkill->pivot->description,
            'category'     => $subSkill->pivot->category,
            'refreshed_at' => $subSkill->pivot->refreshed_at,
        ],
        'agentDefault' => [
            'name'        => ($agentDefault?->pivot->name) ?: $skill->name,
            'description' => ($agentDefault?->pivot->description) ?: $skill->description,
            'category'    => ($agentDefault?->pivot->category) ?: $skill->category,
        ],
    ]) }}">
</div>
@endsection
