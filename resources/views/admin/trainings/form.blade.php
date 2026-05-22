@extends('layouts.admin')

@section('title', (isset($training) ? 'Edit' : 'New') . ' Training - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="training-form"
    data-props="{{ json_encode([
        'user'      => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken' => csrf_token(),
        'training'  => $training ?? null,
        'errors'    => $errors->all(),
    ]) }}">
</div>
@endsection
