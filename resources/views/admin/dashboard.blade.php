@extends('layouts.admin')

@section('title', 'Admin Dashboard - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="dashboard"
    data-props="{{ json_encode([
        'user'        => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'   => csrf_token(),
        'stats'       => $stats,
        'recentUsers' => $recentUsers,
    ]) }}">
</div>
@endsection
