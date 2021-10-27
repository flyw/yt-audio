@extends('backend.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Download
        </h1>
    </section>
    <div class="content">
        {{-- @include('adminlte-templates::common.errors') --}}
        <div class="card card-accent-primary shadow-lg">

            <div class="card-body">
                    {!! Form::open(['route' => 'downloads.store']) !!}
                    <div class="row">
                        @include('downloads.fields')
                    </div>
                    {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
