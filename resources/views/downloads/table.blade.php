@php
    function human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
function getExt($path) {
    return @pathinfo($path)['extension'];
}
@endphp
<div class="row">
        @foreach($downloads as $download)
        <div class="col-6 col-md-3">
            <div class="card">
                <div class="embed-responsive embed-responsive-21by9">
                    <img class="embed-responsive-item card-img-top" src="{!! url('storage/'.$download->thumbnail_path) !!}"/>
                </div>
                <div class="card-body">
                    {!! $download->title !!}
                    <br/>
                    <span class="badge badge-warning">
                        {!! $download->selected_format !!}
                    </span>

                    <span class="badge badge-secondary">
                        {!! human_filesize(@filesize(storage_path("app/public/".$download->path))) !!}
                    </span>

                    <br/>

                </div>
                <div class="card-footer">

                    {!! Form::open(['route' => ['downloads.destroy', $download->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        @if(!$download->path)
                            <a class="btn btn-linkedin btn-sm" href="{!! route('downloads.show', $download->id) !!}">
                                <i class="fas fa-edit"></i> 准备
                            </a>
                        @endif
                        @if($download->path)
                        <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#collapseDownload{!! $download->id !!}" aria-expanded="false" aria-controls="collapseDownload{!! $download->id !!}">
                            <i class="fas fa-download"></i> 下载
                        </button>
                        @endif

                        <button class="btn btn-secondary btn-sm" type="button" data-toggle="collapse" data-target="#collapseLink{!! $download->id !!}" aria-expanded="false" aria-controls="collapseLink{!! $download->id !!}">
                            <i class="fas fa-external-link-alt"></i> 原始连接
                        </button>

                        {!! Form::button('<i class="fas fa-trash-alt"></i> 删除', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}

                    <div class="collapse" id="collapseDownload{!! $download->id !!}">
                        <div class="card card-body">
                            <span class="code small">
                                scp root@b.moefunny.com:{!! storage_path("app/public/".$download->path) !!}
                                "{!! $download->title !!}.{!! getExt($download->path) !!}"
                            </span>
                        </div>
                    </div>
                    <div class="collapse" id="collapseLink{!! $download->id !!}">
                        <div class="card card-body">
                            <span class="code small">
                                {!! $download->video_id !!}
                            </span>
                        </div>
                    </div>


                </div>
            </div>

        </div>
        @endforeach
</div>
{!! $downloads->links() !!}
