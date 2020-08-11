@extends('backend.layouts.app')

@push('after-styles')
    {{ style('bower_components/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css') }}
@endpush

@push('after-scripts')
    {!! script('bower_components/seiyria-bootstrap-slider/dist/bootstrap-slider.min.js') !!}
    <script>
        $(document).ready(function () {
            var player = document.getElementById("player");
           $("#play-btn").click(function () {
               if (player.paused) {
                   player.play();
                   $("#play-btn").removeClass('fa-play-circle')
                   .addClass('fa-pause-circle');

               }
               else {
                   $("#play-btn").addClass('fa-play-circle')
                   .removeClass('fa-pause-circle');
                   player.pause();
               }
           });

            var mySlider = $("input.slider").slider()
                .on('change', function(ev){
                    var newRate = $('input.slider').data('slider').getValue();
                    console.log(newRate);
                    player.playbackRate = newRate;
                });
        });
    </script>
@endpush

@section('content')
    <div class="col-lg-6 col-sm-12 p-0">
        @include('flash::message')
        <div class="card card-primary shadow-lg">
            <div class="embed-responsive embed-responsive-16by9">
                <div class="embed-responsive-item d-flex">
                    <img class="w-100 align-self-center" src="{!! url('storage/'.$entity->thumbnail) !!}"/>
                </div>
            </div>
            <div class="card-body bg-dark text-white p-1 m-0 pt-2 pb-2">
                <div class="w-100">
                    <audio controls id="player" class="w-100" preload="auto">
                        <source src="{!! url('storage').'/'.$entity->video_uri !!}" type="audio/mp4">
                    </audio>
                </div>
                <div class="d-flex justify-content-center">
                    <span class="badge badge-light badge-pill">{{\Carbon\Carbon::parse($entity->published)->diffForHumans()}}</span>
                </div>
                <div class="card-text lead pr-3 pl-3">
                    <span>{!! $entity->title !!}</span>
                </div>
                <div class="d-flex flex-row justify-content-between mt-2  pr-3 pl-3">
                    <span class="badge badge-success">
                            <span class="lead">
                                <i class="far fa-eye"></i> {!! floor($entity->views_count/1000) !!}k
                            </span>
                        </span>

                    <span class="badge badge-warning">
                            <span class="lead">
                                <i class="far fa-check-circle"></i> {!! $entity->rating_average !!}
                            </span>
                        </span>

                    <span class="badge badge-primary">
                            <span class="lead">
                                <i class="far fa-play-circle"></i> {!! $entity->fileSize !!}
                            </span>
                        </span>

                    <span class="badge badge-secondary">
                            <span class="lead">
                                <i class="far fa-clock"></i> {!! $entity->duration !!}
                            </span>
                        </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="col-12 p-5">
                    <input class="form-control slider" style="width: 100%"
                           type="text"
                           name="rate"
                           data-provide="slider"
                           data-slider-ticks="[1,1.25,1.5,1.75,2]"
                           data-slider-ticks-labels='[1,1.25,1.5,1.75,2]'
                           data-slider-min="1"
                           data-slider-max="2"
                           data-slider-step="0.25"
                           data-slider-value="1"
                           data-slider-tooltip="hide"
                    >
                </div>
                <div class="col-12 d-flex justify-content-between pr-5 pl-5 pt-0 pb-3">
                    <div class="d-flex flex-column position-relative align-self-center"
                         onclick='document.getElementById("player").currentTime -=10'>
                        <i class="fas fa-undo fa-4x text-secondary"></i>
                        <span class="badge badge-light position-absolute align-self-center" style="bottom: 1.1rem">10s</span>
                    </div>

                    <i id="play-btn" class="fas fa-play-circle fa-5x text-secondary"></i>

                    <div class="d-flex flex-column position-relative align-self-center"
                         onclick='document.getElementById("player").currentTime +=10'>
                        <i class="fas fa-redo fa-4x text-secondary"></i>
                        <span class="badge badge-light position-absolute align-self-center" style="bottom: 1.1rem">10s</span>
                    </div>



                </div>

{{--                <audio controls id="audio_player" class="w-100">--}}
{{--                    <source src="{!! url('storage').'/'.$entity->audio_file_uri !!}" type="audio/webm">--}}
{{--                    Your browser does not support the audio element.--}}
{{--                </audio>--}}

            </div>
            <div class="card-body">
                <a class="btn btn-secondary btn-block" href="{{route("entities.edit",[$entity->id])}}">
                    <i class="fas fa-download"></i> Download Again
                </a>
            </div>
            <div class="card-body">
            {!! preg_replace('/\n/',"<br/>",$entity->description) !!}

            </div>
        </div>
    </div>
@endsection
