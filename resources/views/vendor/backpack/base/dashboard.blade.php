@extends(backpack_view('blank'))
@php
    $widgets['before_content'][] = [
        'type'        => 'jumbotron',
        'heading'     => 'Welcome to Biometric Presence',
        'content'     => 'Spike Solution Prototype for Biometric Presence',
        'button_link' => backpack_url('logout'),
        'button_text' => trans('backpack::base.logout'),
    ];
@endphp
@section('content')

@endsection