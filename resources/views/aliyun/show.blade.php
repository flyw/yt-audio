@extends('backend.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Download
        </h1>
    </section>
    <div class="content">
        <div class="card card-accent-primary shadow-lg">
            <div class="card-body">
                <div class="row" style="padding: 20px">
                    @include('downloads.show_fields')
                    <a href="{!! route('downloads.index') !!}" class="btn btn-default"><i class="fas fa-reply"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
