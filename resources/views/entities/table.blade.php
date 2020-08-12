@push('after-scripts')
    <script>
        function setViewed(id) {
            $("#item-card-"+id)
                .addClass('bg-dark text-white')
                .removeClass('text-dark')
        }
    </script>
@endpush
<div class="table-responsive">
    <div class="row">
        @include('flash::message')
    @foreach($entities as $entity)
        <div class="col-sm-12 col-md-6 col-lg-4 p-0">

            <div class="card card-primary ">
                <div class="card-body p-0 position-relative">

                    <a href="{{route("entities.show",[$entity->id])}}" onclick="setViewed({{$entity->id}})">
                        <div class="embed-responsive embed-responsive-16by9">
                            <div class="embed-responsive-item d-flex">
                                <img class="w-100 align-self-center" src="{!! url('storage/'.$entity->thumbnail) !!}"/>
                            </div>
                        </div>
                    </a>
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


                <div class="card-body p-1 m-0 pt-2 pb-2 @if($entity->is_viewed) bg-dark @endif" id="item-card-{{$entity->id}}">
                    <a href="{{route("entities.show",[$entity->id])}}" onclick="setViewed({{$entity->id}})">
                    <div class="card-text lead @if($entity->is_viewed) text-white @else text-dark @endif" >
                        <span>{!! $entity->title !!}</span>
                    </div>
                    </a>
                </div>
                @if ($entity->video_uri == "null" || $entity->video_uri == null)
                    <div class="card-footer m-0">
                        <a class="btn btn-block btn-danger btn-sm" href="{{route("entities.edit",[$entity->id])}}">
                            <i class="fas fa-download fa"></i> Retry Download
                        </a>
                    </div>
                @endif
            </div>
        </div>

    @endforeach
    </div>

    <div class="d-flex justify-content-center">
    {{ $entities->links() }}
    </div>
</div>
