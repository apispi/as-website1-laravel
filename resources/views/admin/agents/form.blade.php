@extends('layouts.admin')

@section('title', (isset($agent) ? 'Edit' : 'New') . ' Agent - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="agent-form"
    data-props="{{ json_encode([
        'user'           => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'      => csrf_token(),
        'agent'          => $agent ?? null,
        'errors'         => $errors->all(),
        'allSkills'          => $skills->groupBy('category')->map(fn($g) => $g->values())->toArray(),
        'agentSkillIds'      => isset($agent) ? $agent->skills->pluck('id')->toArray() : [],
        'allConnectors'      => $connectors->groupBy('category')->map(fn($g) => $g->values()->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'icon' => $c->icon]))->toArray(),
        'agentConnectorIds'  => isset($agent) ? $agent->connectors->pluck('id')->toArray() : [],
    ]) }}">
</div>
@endsection
