@extends('backend.layouts.pdf')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1>{{$filename}}</h1>
        </div>
        <div class="card-body" style="padding: 0;">
            @include($baseRoute.'.table')
        </div>
    </div>
@endsection
