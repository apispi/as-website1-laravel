@extends('layouts.admin')

@section('title', 'Leads - Admin - ApiSpi')

@section('content')
<div id="admin-app"
    data-page="leads"
    data-props="{{ json_encode([
        'user'         => ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email],
        'csrfToken'    => csrf_token(),
        'leads'        => $leads->map(fn($l) => [
            'id'           => $l->id,
            'name'         => $l->name,
            'email'        => $l->email,
            'company'      => $l->company,
            'role'         => $l->role,
            'use_case'     => $l->use_case,
            'ip_address'   => $l->ip_address,
            'source'       => $l->source ?? 'avatar',
            'partner_type' => $l->partner_type,
            'created_at'   => $l->created_at?->toISOString(),
        ])->values(),
        'flashSuccess' => session('success', ''),
    ]) }}">
</div>
@endsection
