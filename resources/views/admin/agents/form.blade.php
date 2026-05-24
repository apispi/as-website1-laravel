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
        'allSkills'          => $skills->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'category' => $s->category])->toArray(),
        'agentSkillIds'      => isset($agent) ? $agent->skills->pluck('id')->toArray() : [],
        'allConnectors'      => $connectors->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'icon' => $c->icon, 'category' => $c->category])->toArray(),
        'agentConnectorIds'  => isset($agent) ? $agent->connectors->pluck('id')->toArray() : [],
        'initialTab'         => session('active_tab', 'details'),
    ]) }}">
</div>
@endsection
