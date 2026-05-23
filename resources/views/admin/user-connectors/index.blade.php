@extends('layouts.admin')

@section('title', 'User Connectors - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="user-connectors"
    data-props="{{ json_encode([
        'user'           => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'      => csrf_token(),
        'userConnectors' => $userConnectors,
        'flashSuccess'   => session('success', ''),
        'flashError'     => session('error', ''),
    ]) }}">
</div>
@endsection
