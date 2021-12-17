@extends('backend.layouts.app')
@php
    function human_filesize($bytes, $decimals = 2) {
      $sz = 'BKMGTP';
      $factor = floor((strlen($bytes) - 1) / 3);
      return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }
    function getExt($path) {
        return @pathinfo($path)['extension'];
    }
    function fixTitle($name) {
    $name = str_replace(array_merge(
        array_map('chr', range(0, 31)),
        array('<', '>', ':', '"', '/', '\\', '|', '?', '*')
    ), ' ', $name);
    // maximise filename length to 255 bytes http://serverfault.com/a/9548/44086
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    $name= mb_strcut(pathinfo($name, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($name)) . ($ext ? '.' . $ext : '');
    return $name;
    }
@endphp

@section('content')
    <section class="content-header">
        <h1>Aliyun Drive Uplader Config</h1>
{{--           <a class="btn btn-primary" href="{!! route('downloads.create') !!}"><i class="fas fa-plus-circle"></i> Add New</a>--}}
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card card-accent-primary shadow-lg">
            <div class="card-body">



                <div class="h3 callout callout-danger">
                    控制台快速获取代码
                </div>
                <div class="code-wrapper">
                    <code class="h5 mt-5">
                        <pre>
var data = JSON.parse(localStorage.getItem('token'));
console.log(`refresh_token  =>  ${data.refresh_token}
default_drive_id  =>  ${data.default_drive_id}
`);
                        </pre>
                    </code>
                </div>

                <div class="mt-5">
                    <a class="btn btn-lg btn-primary" href="https://www.aliyundrive.com" target="_blank">前往阿里云盘</a>
                </div>

            </div>
        </div>


        <div class="card card-accent-primary shadow-lg">
            <div class="card-body">

                {!! Form::open(['route' => 'aliyun.store']) !!}
                <div class="row">
                    @foreach($config as $key=>$value)
                        <div class="form-group input-group col-xl-4">
                            <label for="{!! $key !!}">{!! $key !!}</label>
                            {!! Form::text($key, $value, ['class' => 'form-control','placeholder'=>$key]) !!}
                        </div>
                    @endforeach
                    {!! Form::submit('更新', ['class' => ' m-3 btn btn-primary btn-lg']) !!}
                </div>

                {!! Form::close() !!}



            </div>
        </div>


        <div class="card card-accent-primary shadow-lg">
            <div class="card-body overflow-auto">

                @foreach($downloads as $download)
                    <div class="text-nowrap @if(getExt($download->path)!='webm' && getExt($download->path)!='mp4' && getExt($download->path)!='mkv') bg-danger text-dark @endif">

                cp {!! storage_path("app/public/".$download->path) !!} "/tmp/{!! fixTitle($download->title) !!}.{!! getExt($download->path) !!}" ;
                /opt/aliyundrive-uploader/main.py "/tmp/{!! fixTitle($download->title) !!}.{!! getExt($download->path) !!}" ;
                rm -f "/tmp/{!! fixTitle($download->title) !!}.{!! getExt($download->path) !!}"
                    </div>
                @endforeach
            </div>
        </div>


        <div class="card card-accent-primary shadow-lg">
            <div class="card-body">
                    <a class="btn btn-lg btn-light" href="https://github.com/Hidove/aliyundrive-uploader">
                        https://github.com/Hidove/aliyundrive-uploader
                    </a>
            </div>
        </div>

    </div>
@endsection

