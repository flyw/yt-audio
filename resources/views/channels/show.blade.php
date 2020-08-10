@extends('backend.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{$channel->name}}
        </h1>
    </section>
    <div class="content">
        <div class="card card-accent-primary shadow-lg">
            <div class="card-body">
                <div class="row" style="padding: 20px">
                    @include('entities.table')
                </div>
            </div>
        </div>
    </div>
@endsection
