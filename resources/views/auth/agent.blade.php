@extends('layouts.agent-detail')

@section('title', ($subscription->agent->name ?? 'Agent') . ' - ApiSpi')

@section('content')
<div
    id="agent-detail-app"
    data-user="{{ json_encode(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'is_admin' => Auth::user()->is_admin]) }}"
    data-csrf="{{ csrf_token() }}"
    data-subscription="{{ json_encode([
        'id'         => $subscription->id,
        'status'     => $subscription->status,
        'started_at' => $subscription->started_at,
        'expires_at' => $subscription->expires_at,
        'agent'      => $subscription->agent ? [
            'id'          => $subscription->agent->id,
            'name'        => $subscription->agent->name,
            'slug'        => $subscription->agent->slug,
            'description' => $subscription->agent->description,
            'badge'       => $subscription->agent->badge,
            'price'       => $subscription->agent->price,
            'category'    => $subscription->agent->category,
        ] : null,
    ]) }}"
></div>
@endsection
