@extends('layouts.dashboard-training')

@section('title', 'Training - ApiSpi')

@section('content')
<div
    id="dashboard-training-app"
    data-user="{{ json_encode(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'is_admin' => Auth::user()->is_admin]) }}"
    data-csrf="{{ csrf_token() }}"
    data-trainings="{{ json_encode($trainings->map(fn($t) => [
        'id'                  => $t->id,
        'title'               => $t->title,
        'slug'                => $t->slug,
        'category'            => $t->category,
        'format'              => $t->format,
        'duration'            => $t->duration,
        'level'               => $t->level,
        'description'         => $t->description,
        'price'               => $t->price,
        'price_unit'          => $t->price_unit,
        'badge'               => $t->badge,
        'is_featured'         => $t->is_featured,
        'modules_count'       => $t->modules_count,
        'instructor'          => $t->instructor,
        'stripe_payment_link' => $t->stripe_payment_link,
        'checkout_amount'     => $t->checkout_amount,
        'checkout_name'       => $t->checkout_name,
    ])) }}"
></div>
@endsection
