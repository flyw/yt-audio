<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Jobs\EntryDownload;
use App\Models\Entity;
use App\Repositories\ChannelRepository;
use App\Http\Controllers\AppBaseController;
use App\Utils\EntityManager;
use App\Utils\FeedFetcher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ChannelController extends AppBaseController
{
    /** @var  ChannelRepository */
    private $channelRepository;

    public function __construct(ChannelRepository $channelRepo)
    {
        $this->channelRepository = $channelRepo;
    }

    /**
     * Display a listing of the Channel.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->channelRepository->pushCriteria(new RequestCriteria($request));
        $channels = $this->channelRepository->with(['entities' => function ($q)  {
            $q->where('published','>=', Carbon::now()->subDays(2)->setTime(0,0,0))
                ->orderBy('published','DESC');
        }])->orderBy('published', 'DESC')->get();
        foreach ($channels as $channel) {
            $channel->todayCount = $channel->entities->count();
            if ($channel->todayCount == 0) {
                $channel->entities = collect([$channel->entities()->first()]);
            }
            foreach ($channel->entities as $entity) {
                $size = @filesize(storage_path('app/public').'/'.$entity->video_uri);
                $entity->fileSize = $this->human_filesize($size);
            }
        }

        return view('channels.index')
            ->with('channels', $channels);
    }

    /**
     * Show the form for creating a new Channel.
     *
     * @return Response
     */
    public function create()
    {
        return view('channels.create');
    }

    /**
     * Store a newly created Channel in storage.
     *
     * @param CreateChannelRequest $request
     *
     * @return Response
     */
    public function store(CreateChannelRequest $request)
    {
        $input = $request->all();

        $feed = FeedFetcher::fetch($request->get('channel_id'));
        $input['name'] = $feed->title;
        $input['published'] = Carbon::parse($feed->published);
        $channel = $this->channelRepository->create($input);

        foreach ($feed->entry as $entry) {
            EntryDownload::dispatch($channel->id , $entry, true);
        }



        Flash::success('Channel saved successfully.');

        return redirect(route('channels.index'));
    }

    /**
     * Display the specified Channel.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $channel = $this->channelRepository->findWithoutFail($id);

        if (empty($channel)) {
            Flash::error('Channel not found');

            return redirect(route('channels.index'));
        }

        $entities = Entity::where('channel_id', $channel->id)
            ->orderBy('published', 'DESC')
            ->paginate(40);

        foreach ($entities as $entity) {
            $size = @filesize(storage_path('app/public').'/'.$entity->video_uri);
            $entity->fileSize = $this->human_filesize($size);
        }

        return view('channels.show')
            ->with('entities', $entities)
            ->with('channel', $channel);
    }

    /**
     * Show the form for editing the specified Channel.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $channel = $this->channelRepository->findWithoutFail($id);

        if (empty($channel)) {
            Flash::error('Channel not found');

            return redirect(route('channels.index'));
        }

        return view('channels.edit')->with('channel', $channel);
    }

    /**
     * Update the specified Channel in storage.
     *
     * @param  int              $id
     * @param UpdateChannelRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChannelRequest $request)
    {
        $channel = $this->channelRepository->findWithoutFail($id);

        if (empty($channel)) {
            Flash::error('Channel not found');

            return redirect(route('channels.index'));
        }

        $input = $request->all();
        $feed = FeedFetcher::fetch($request->get('channel_id'));
        $input['name'] = $feed->title;
        $input['published'] = Carbon::parse($feed->published);

        $channel = $this->channelRepository->update($input, $id);

        foreach ($feed->entry as $entry) {
            EntryDownload::dispatch($channel->id , $entry);
        }

        Flash::success('Channel updated successfully.');

        return redirect(route('channels.index'));
    }

    /**
     * Remove the specified Channel from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $channel = $this->channelRepository->findWithoutFail($id);

        if (empty($channel)) {
            Flash::error('Channel not found');

            return redirect(route('channels.index'));
        }

        $this->channelRepository->delete($id);

        Flash::success('Channel deleted successfully.');

        return redirect(route('channels.index'));
    }
}
