@extends('layouts.admin')

@section('title', (isset($agent) ? 'Edit' : 'New') . ' Agent - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="agent-form"
    data-props="{{ json_encode([
        'user'      => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken' => csrf_token(),
        'agent'     => $agent ?? null,
        'errors'    => $errors->all(),
    ]) }}">
</div>
@endsection
