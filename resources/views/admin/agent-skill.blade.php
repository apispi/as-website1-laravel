@extends('layouts.admin')

@section('title', $agentSkill->pivot->name ?: $skill->name . ' — ' . $agent->name . ' - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="agent-skill-detail"
    data-props="{{ json_encode([
        'user'      => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken' => csrf_token(),
        'agent' => [
            'id'   => $agent->id,
            'name' => $agent->name,
        ],
        'skill' => [
            'id'              => $skill->id,
            'slug'            => $skill->slug,
            'catalog_name'    => $skill->name,
            'catalog_desc'    => $skill->description,
            'catalog_category'=> $skill->category,
        ],
        'pivot' => [
            'name'         => $agentSkill->pivot->name,
            'description'  => $agentSkill->pivot->description,
            'category'     => $agentSkill->pivot->category,
            'refreshed_at' => $agentSkill->pivot->refreshed_at,
        ],
    ]) }}">
</div>
@endsection
