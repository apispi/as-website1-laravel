@extends('layouts.admin')

@section('title', 'Agents - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="agents"
    data-props="{{ json_encode([
        'user'         => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'    => csrf_token(),
        'agents'       => $agents->map(fn($a) => array_merge($a->toArray(), [
            'skills' => $a->skills->map(fn($s) => ['id' => $s->id, 'name' => $s->pivot->name ?: $s->name])->values(),
        ]))->values(),
        'connectors'   => $connectors,
        'skills'       => $skills->map(fn($s) => ['id' => $s->id, 'slug' => $s->slug, 'name' => $s->name, 'description' => $s->description, 'category' => $s->category, 'is_active' => $s->is_active])->values(),
        'flashSuccess' => session('success', ''),
        'flashError'   => session('error', ''),
    ]) }}">
</div>
@endsection
