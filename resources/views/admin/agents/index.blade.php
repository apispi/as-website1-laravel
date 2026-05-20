@extends('layouts.admin')

@section('title', 'Agents - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="agents"
    data-props="{{ json_encode([
        'user'      => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken' => csrf_token(),
        'agents'    => $agents,
    ]) }}">
</div>
@endsection
