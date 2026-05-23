@extends('layouts.admin')

@section('title', 'Connectors - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="connectors"
    data-props="{{ json_encode([
        'user'         => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'    => csrf_token(),
        'connectors'   => $connectors,
        'flashSuccess' => session('success', ''),
        'flashError'   => session('error', ''),
    ]) }}">
</div>
@endsection
