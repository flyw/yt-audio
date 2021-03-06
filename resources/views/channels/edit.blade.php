@extends('backend.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Channel
        </h1>
   </section>
   <div class="content">
       {{-- @include('adminlte-templates::common.errors') --}}
       <div class="card card-accent-primary shadow-lg">
           <div class="card-body">
                   {!! Form::model($channel, ['route' => ['channels.update', $channel->id], 'method' => 'patch']) !!}
                   <div class="row">
                        @include('channels.fields')
                    </div>
                   {!! Form::close() !!}
           </div>
       </div>
   </div>
@endsection
