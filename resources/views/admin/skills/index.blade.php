@extends('layouts.admin')

@section('title', 'Skills - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="skills"
    data-props="{{ json_encode([
        'user'         => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'    => csrf_token(),
        'skills'       => $skills,
        'flashSuccess' => session('success', ''),
        'flashError'   => session('error', ''),
    ]) }}">
</div>
@endsection
