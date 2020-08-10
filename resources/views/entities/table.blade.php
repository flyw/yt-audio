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
                <a href="{{route("entities.show",[$entity->id])}}" onclick="setViewed({{$entity->id}})">
                    <div class="embed-responsive embed-responsive-16by9">
                        <div class="embed-responsive-item d-flex">
                            <img class="w-100 align-self-center" src="{!! url('storage/'.$entity->thumbnail) !!}"/>
                        </div>
                    </div>
                </a>
                <div class="card-body p-1 m-0 pt-2 pb-2 @if($entity->is_viewed) bg-dark @endif" id="item-card-{{$entity->id}}">
                    <div class="d-flex justify-content-center">
                        <span class="badge bg-dark badge-pill">{{$entity->published}}</span>
                    </div>
                    <a href="{{route("entities.show",[$entity->id])}}" onclick="setViewed({{$entity->id}})">
                    <div class="card-text lead @if($entity->is_viewed) text-white @else text-dark @endif" >
                        <span>{!! $entity->title !!}</span>
                    </div>
                    </a>
                    <div class="d-flex flex-row justify-content-between mt-2">
                        <span class="badge badge-info badge-pill">
                            <span class="lead">
                                <i class="far fa-eye"></i> {!! floor($entity->views_count/1000) !!}k
                            </span>
                        </span>

                        <span class="badge badge-success badge-pill">
                            <span class="lead font-weight-bolder">
                                <i class="far fa-play-circle"></i> {!! $entity->fileSize !!}
                            </span>
                        </span>

                        <span class="badge badge-secondary badge-pill">
                            <span class="lead font-weight-bolder">
                                <i class="far fa-clock"></i> {!! $entity->duration !!}
                            </span>
                        </span>

                        @if ($entity->video_uri == "null")
                            <span class="badge badge-danger d-flex">
                                <a class="text-white pr-1 pl-1 align-self-center" href="{{route("entities.edit",[$entity->id])}}">
                                    <i class="fas fa-download fa"></i>
                                </a>
                            </span>
                        @endif

                    </div>
                </div>
            </div>
        </div>

    @endforeach
    </div>

    <div class="d-flex justify-content-center">
    {{ $entities->links() }}
    </div>
</div>
