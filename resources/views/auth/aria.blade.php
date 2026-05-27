@extends('layouts.aria-chat')

@section('title', 'Aria AI — ApiSpi')

@section('content')
<div
    id="aria-app"
    data-user="{{ json_encode(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'is_admin' => Auth::user()->is_admin]) }}"
    data-csrf="{{ csrf_token() }}"
></div>
@endsection
