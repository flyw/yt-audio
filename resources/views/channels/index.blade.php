@extends('backend.layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="float-left">Channels</h1>
        <h1 class="float-right">
           <a class="btn btn-primary" href="{!! route('channels.create') !!}"><i class="fas fa-plus-circle"></i> Add New</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="card card-accent-primary shadow-lg">
            <div class="card-body p-0 pt-3 pb-3">
                    @include('channels.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection

