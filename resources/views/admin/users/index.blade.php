@extends('layouts.admin')

@section('title', 'Users - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="users"
    data-props="{{ json_encode([
        'user'         => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'    => csrf_token(),
        'users'        => $users,
        'flashSuccess' => session('success', ''),
        'flashError'   => session('error', ''),
    ]) }}">
</div>
@endsection
