@extends('backend.layouts.app')

@section('content')
    <section class="content-header">
        <h1>Downloads</h1>
            {!! Form::open(['route' => 'downloads.store']) !!}
            <div class="row">
                <div class="form-group col-sm-12 input-group">
                    {!! Form::text('video_id', null, ['class' => 'form-control','placeholder'=>'Video ID']) !!}
                    {!! Form::submit('下载', ['class' => 'btn btn-primary']) !!}
                </div>

            </div>
            {!! Form::close() !!}

{{--           <a class="btn btn-primary" href="{!! route('downloads.create') !!}"><i class="fas fa-plus-circle"></i> Add New</a>--}}
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="card card-accent-primary shadow-lg">
            <div class="card-body">
                    @include('downloads.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection

