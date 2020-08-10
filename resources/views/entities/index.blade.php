@extends('backend.layouts.app')

@section('content')

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="card card-accent-primary shadow-lg">
            <div class="card-body">
                    @include('entities.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection

