@push('after-scripts')
    <script>
        $(document).ready(function () {
            let videoCode = "";
            let audioCode = "";
            $(".av").click(function (evt) {
                videoCode = "";
                audioCode = "";
                $("#selected_format").val($(evt.currentTarget).data('code'));
            })

            $(".video-only").click(function (evt) {
                videoCode = $(evt.currentTarget).data('code');
                $("#selected_format").val(videoCode+"+"+audioCode);
            })
            $(".audio-only").click(function (evt) {
                audioCode = $(evt.currentTarget).data('code');
                $("#selected_format").val(videoCode+"+"+audioCode);
            })
        })
    </script>
@endpush
<!-- Video Id Field -->
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <p class="card-text">
                {!! $download->video_id !!}
            </p>
        </div>
    </div>
</div>

<div class="col-12">
    <h3>
        {!! $download->title !!}
    </h3>
</div>

<div class="col-12 mb-5">
    <img src="{!! url("storage/".$download->thumbnail_path) !!}"/>
</div>

<!-- Selected Format Field -->
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">{!! Form::label('selected_format', 'Selected Format:') !!}</h6>
            <p class="card-text">
            {!! Form::model($download, ['route' => ['downloads.update', $download->id], 'method' => 'patch']) !!}
            <div class="row">

{{--                {!! Form::hidden('video_id', null, ['class' => 'form-control']) !!}--}}

                <!-- Selected Format Field -->
                <div class="form-group col-sm-6">
                    <input type="text" class="form-control" id="selected_format" name="selected_format" value="{!! $download->selected_format !!}">
                </div>

                <div class="form-group col-12">
                    {!! Form::submit('Start Download', ['class' => 'btn btn-primary']) !!}
                </div>
            </div>
            {!! Form::close() !!}


            </p>
        </div>
    </div>
</div>



<!-- Available Format Field -->
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">A+V</h6>
            <p class="card-text">
                @foreach($download->available_format_object as $format)
                    @if($format->type == 'av')
                        <button class="btn btn-primary btn-lg m-2 av" data-code="{!! $format->code !!}">
                            <span class="badge badge-dark">{!! $format->resolution !!}</span>
                            <span class="badge badge-light">{!! $format->extension !!}</span>
                            <span class="badge badge-secondary">{!! $format->fileSize !!}</span>
                            <span class="badge badge-warning">{!! $format->code !!}</span>
                        </button>
                    @endif
                @endforeach
            </p>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="card">
        <div class="card-body row">

<div class="col-3">
    <div class="card-body">
        <h6 class="card-title">V <span class="badge badge-warning">webm</span></h6>
        <p class="card-text">
            @foreach($download->available_format_object as $format)
                @if($format->type == 'v' && $format->extension == 'webm')
                    <button class="btn btn-dropbox m-2 video-only" data-code="{!! $format->code !!}">
                        <span class="badge badge-dark">{!! $format->resolution !!}</span>
                        <span class="badge badge-secondary">{!! $format->fileSize !!}</span>
                        <span class="badge badge-warning">{!! $format->code !!}</span>
                    </button>
                @endif
            @endforeach
        </p>
    </div>
</div>
<div class="col-6">
    <div class="card-body">
        <h6 class="card-title">V <span class="badge badge-warning">mp4</span></h6>
        <p class="card-text">
            @foreach($download->available_format_object as $format)
                @if($format->type == 'v' && $format->extension == 'mp4')
                    <button class="btn btn-dropbox m-2 video-only" data-code="{!! $format->code !!}">
                        <span class="badge badge-dark">{!! $format->resolution !!}</span>
                        <span class="badge badge-secondary">{!! $format->fileSize !!}</span>
                        <span class="badge badge-warning">{!! $format->code !!}</span>
                    </button>
                @endif
            @endforeach
        </p>
    </div>
</div>

<div class="col-3">
    <div class="card-body">
        <h6 class="card-title">V <span class="badge badge-warning">other</span></h6>
        <p class="card-text">
            @foreach($download->available_format_object as $format)
                @if($format->type == 'v' && $format->extension != 'mp4' && $format->extension != 'webm')
                    <button class="btn btn-dropbox m-2 video-only" data-code="{!! $format->code !!}">
                        <span class="badge badge-dark">{!! $format->resolution !!}</span>
                        <span class="badge badge-light">{!! $format->extension !!}</span>
                        <span class="badge badge-secondary">{!! $format->fileSize !!}</span>
                        <span class="badge badge-warning">{!! $format->code !!}</span>
                    </button>
                @endif
            @endforeach
        </p>
    </div>
</div>
        </div>
    </div>
</div>



<div class="col-12">
    <div class="card">
        <div class="card-body row">
            <div class="col-3">
                <div class="card-body">
                    <h6 class="card-title">A <span class="badge badge-warning">webm</span></h6>
                    <p class="card-text">
                        @foreach($download->available_format_object as $format)
                            @if($format->type == 'a' && $format->extension == 'webm')
                                <button class="btn btn-html5 m-2 audio-only" data-code="{!! $format->code !!}">
                                    <span class="badge badge-dark">{!! $format->resolution !!}</span>
                                    <span class="badge badge-secondary">{!! $format->fileSize !!}</span>
                                    <span class="badge badge-warning">{!! $format->code !!}</span>
                                </button>
                            @endif
                        @endforeach
                    </p>
                </div>
            </div>
            <div class="col-6">
                <div class="card-body">
                    <h6 class="card-title">A <span class="badge badge-warning">m4a</span></h6>
                    <p class="card-text">
                        @foreach($download->available_format_object as $format)
                            @if($format->type == 'a' && $format->extension == 'm4a')
                                <button class="btn btn-html5 m-2 audio-only" data-code="{!! $format->code !!}">
                                    <span class="badge badge-dark">{!! $format->resolution !!}</span>
                                    <span class="badge badge-secondary">{!! $format->fileSize !!}</span>
                                    <span class="badge badge-warning">{!! $format->code !!}</span>
                                </button>
                            @endif
                        @endforeach
                    </p>
                </div>
            </div>

            <div class="col-3">
                <div class="card-body">
                    <h6 class="card-title">A <span class="badge badge-warning">other</span></h6>
                    <p class="card-text">
                        @foreach($download->available_format_object as $format)
                            @if($format->type == 'a' && $format->extension != 'm4a' && $format->extension != 'webm')
                                <button class="btn btn-html5 m-2 audio-only" data-code="{!! $format->code !!}">
                                    <span class="badge badge-dark">{!! $format->resolution !!}</span>
                                    <span class="badge badge-light">{!! $format->extension !!}</span>
                                    <span class="badge badge-secondary">{!! $format->fileSize !!}</span>
                                    <span class="badge badge-warning">{!! $format->code !!}</span>
                                </button>
                            @endif
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Path Field -->
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">{!! Form::label('path', 'Path:') !!}</h6>
            <p class="card-text">
                {!! $download->path !!}
            </p>
        </div>
    </div>
</div>
