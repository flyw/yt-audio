@extends('backend.layouts.app')

@push('after-styles')
    {{ style('bower_components/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css') }}
@endpush

@push('after-scripts')

    {!! script('bower_components/seiyria-bootstrap-slider/dist/bootstrap-slider.min.js') !!}
    {!! script('bower_components/hls.js/dist/hls.min.js') !!}
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

            @if(preg_match('/m3u8/', $entity->video_uri))
                var video = document.getElementById('player');
                var videoSrc = "{!! url('storage').'/'.$entity->video_uri !!}";
                if (Hls.isSupported()) {
                    var hls = new Hls();
                    hls.loadSource(videoSrc);
                    hls.attachMedia(video);
                }
                else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = videoSrc;
                }
            @endif
        });
    </script>
@endpush

@section('content')
    <div class="col-lg-6 col-sm-12 p-0">
        @include('flash::message')
        <div class="card card-primary shadow-lg">
            <div class="card-body p-0 position-relative">
                <div class="embed-responsive embed-responsive-16by9">
                    <div class="embed-responsive-item d-flex">
                        <img class="w-100 align-self-center" src="{!! url('storage/'.$entity->thumbnail) !!}"/>
                    </div>
                </div>

                <div class="d-flex flex-column justify-content-between p-2 position-absolute" style="bottom: 0">
                    @if ($entity->viewed_index == "1")
                        <div class="lead">
                            <span class="badge badge-danger bg-white text-danger border border-danger">
                                 Live Now
                            </span>
                        </div>
                    @endif

                    <div class="lead">
                            <span class="badge badge-info">
                                <i class="far fa-eye"></i> {!! floor($entity->views_count/1000) !!}k
                            </span>
                    </div>

                    <div class="lead">
                            <span class="badge badge-success">
                                <i class="far fa-play-circle"></i> {!! $entity->fileSize !!}
                            </span>
                    </div>

                    <div class="lead">
                            <span class="badge badge-secondary">
                                <i class="far fa-clock"></i> {!! $entity->duration !!}
                            </span>
                    </div>

                    <div class="lead">
                            <span class="badge bg-dark">
                                {{\Carbon\Carbon::parse($entity->published)->diffForHumans()}}
                            </span>
                    </div>

                </div>

            </div>
            <div class="card-body bg-dark text-white p-0 m-0 ">
                <div class="w-100">
                    <audio controls id="player" class="w-100 m-0 p-0" preload="auto" stretch="true">
                        <source src="{!! url('storage').'/'.$entity->video_uri !!}" type="video/mp4">
                    </audio>
                </div>
                <div class="card-text lead p-3">
                    <span>{!! $entity->title !!}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="col-12 pt-0 mt-3 pb-3 px-3">
                    <input class="form-control slider" style="width: 100%"
                           type="text"
                           name="rate"
                           data-provide="slider"
                           data-slider-ticks="[1,1.25,1.5,1.75,2]"
                           data-slider-ticks-labels='[1,1.25,1.5,1.75,2]'
                           data-slider-min="1"
                           data-slider-max="3"
                           data-slider-step="0.25"
                           data-slider-value="1"
                           data-slider-tooltip="hide"
                    >
                </div>
                <div class="col-12 d-flex justify-content-between px-2 pt-0 pb-3">
                    <div class="d-flex flex-column position-relative align-self-center"
                         onclick='document.getElementById("player").currentTime -=10'>
                        <span class="badge badge-light position-absolute align-self-center"  style="bottom: 0.8rem">10s</span>
                        <i class="fas fa-undo fa-3x text-secondary" style="z-index: 1"></i>
                    </div>

                    <div class="d-flex flex-column position-relative align-self-center"
                         onclick='document.getElementById("player").currentTime -=60'>
                        <i class="fas fa-undo fa-2x text-secondary"></i>
                        <span class="badge badge-light">60s</span>
                    </div>

                    <i id="play-btn" class="fas fa-play-circle fa-4x text-secondary"></i>


                    <div class="d-flex flex-column position-relative align-self-center"
                         onclick='document.getElementById("player").currentTime +=60'>
                        <i class="fas fa-redo fa-2x text-secondary"></i>
                        <span class="badge badge-light" style="bottom: 1.1rem">60s</span>
                    </div>

                    <div class="d-flex flex-column position-relative align-self-center"
                         onclick='document.getElementById("player").currentTime +=10'>
                        <i class="fas fa-redo fa-3x text-secondary" style="z-index: 1"></i>
                        <span class="badge badge-light position-absolute align-self-center" style="bottom: 0.8rem">10s</span>
                    </div>
                </div>

{{--                <audio controls id="audio_player" class="w-100">--}}
{{--                    <source src="{!! url('storage').'/'.$entity->audio_file_uri !!}" type="audio/webm">--}}
{{--                    Your browser does not support the audio element.--}}
{{--                </audio>--}}

            </div>
            <div class="card-body">
            {!! preg_replace('/\n/',"<br/>",$entity->description) !!}

            </div>

            <div class="card-body">
                <a class="btn btn-secondary btn-block"
                   href="{!! preg_replace('/^https:/','vlc:',url('storage').'/'.$entity->video_uri)!!}">
                    <i class="fab fa-youtube"></i> Play by VLC
                </a>
            </div>

            <div class="card-body">
                <a class="btn btn-secondary btn-block" href="{{route("entities.edit",[$entity->id])}}">
                    <i class="fas fa-download"></i> Download Again
                </a>
            </div>
        </div>
    </div>
@endsection
