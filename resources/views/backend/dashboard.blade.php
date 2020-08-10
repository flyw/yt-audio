@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')
    <div class="row">
        @stack('dashboard-small-cards')
    </div>
    @stack('dashboard')
    <div class="row">
        @stack('dashboard-tiny-cards')
    </div>
@endsection
