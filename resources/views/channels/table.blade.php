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
    @foreach($channels as $channel)
        @if($channel->entities->first() == null)
            @continue
        @endif
        <div class="col-sm-12 col-md-6 col-lg-4 p-0">
            <div class="card card-primary">
                <a href="{!! route('channels.show', [$channel->id]) !!}">
                    <div class="embed-responsive embed-responsive-16by9">
                        <div class="embed-responsive-item d-flex">
                            <img class="w-100 align-self-center" src="{!! url('storage/'.optional($channel->entities->first())->thumbnail) !!}"/>
                        </div>
                    </div>
                </a>
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="mr-auto d-flex flex-column">
                            <span class="card-text lead bold">{!! $channel->name !!}</span>
                            <span class="small badge
                                @if($channel->todayCount) badge-warning
                                @else badge-secondary @endif"
                            >
                                Today: {!! $channel->todayCount !!}
                            </span>
                        </div>
                        {!! Form::open(['route' => ['channels.destroy', $channel->id], 'method' => 'delete']) !!}
                        <span class="btn-group" role="group">
                            <a href="{!! route('channels.edit', [$channel->id]) !!}" class='btn btn-primary btn-xs'><i class="fas fa-pencil-alt"></i></a>
                        {!! Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </span>
                        {!! Form::close() !!}
                    </div>
                </div>

                    @foreach($channel->entities as $entity)
                    <a href="{{route("entities.show",['entity' => $entity->id])}}" class="text-dark" onclick="setViewed({{$entity->id}})">
                    <div id="item-card-{{$entity->id}}" class="card-footer d-flex flex-row align-items-center p-0 m-0 @if($entity->is_viewed) bg-dark text-white @endif">
                        <div class="pr-2">
                            <div class="embed-responsive embed-responsive-16by9" style="width: 6rem">
                                <div class="embed-responsive-item d-flex">
                                    <img class="w-100 align-self-center" src="{!! url('storage/'.$entity->thumbnail) !!}"/>
                                </div>
                            </div>

                        </div>
                        <span>{!! $entity->title !!}
                            <span class="badge badge-success">
                                <i class="fas fa-file-movie-o"></i> {!! $entity->fileSize !!}
                            </span>
                            <span class="badge badge-secondary">
                                <i class="fas fa-play"></i> {!! $entity->duration !!}
                            </span>
                            <span class="badge bg-dark">
                                {{\Carbon\Carbon::parse($entity->published)->diffForHumans()}}</span>
                        </span>
                    </div>
                    </a>
                    @endforeach

            </div>
        </div>
    @endforeach
</div>
