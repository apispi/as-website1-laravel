@extends('layouts.admin')

@section('title', isset($skill) ? 'Edit Skill - Admin - ApiSpi' : 'New Skill - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="skill-form"
    data-props="{{ json_encode([
        'user'      => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken' => csrf_token(),
        'skill'     => $skill ?? null,
        'errors'    => $errors->all(),
    ]) }}">
</div>
@endsection
