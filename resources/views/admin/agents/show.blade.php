@extends('layouts.admin')

@section('title', $agent->name . ' - Agents - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="agent-detail-admin"
    data-props="{{ json_encode([
        'user'       => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'  => csrf_token(),
        'agent'      => $agent,
        'skills'     => $agent->skills->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'category' => $s->category])->values(),
        'connectors' => $agent->connectors->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'icon' => $c->icon, 'category' => $c->category, 'is_oauth' => $c->is_oauth ?? false])->values(),
    ]) }}">
</div>
@endsection
