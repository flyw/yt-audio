@extends('backend.layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Entity
        </h1>
   </section>
   <div class="content">
       {{-- @include('adminlte-templates::common.errors') --}}
       <div class="card card-accent-primary shadow-lg">
           <div class="card-body">
                   {!! Form::model($entity, ['route' => ['entities.update', $entity->id], 'method' => 'patch']) !!}
                   <div class="row">
                        @include('entities.fields')
                    </div>
                   {!! Form::close() !!}
           </div>
       </div>
   </div>
@endsection
