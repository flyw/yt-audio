<?php

namespace App\Jobs;

use App\Models\Entity;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Joydata\Utilities\Utils\ImageFile\ImageFile;

class EntryDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $channelId = null;
    protected $itemString = null;
    protected $disableDownload = false;

    /**
     * Create a new job instance.
     *
     * @param $channelId
     * @param $item
     * @param bool $disableDownload
     */
    public function __construct($channelId, $item, $disableDownload = false)
    {
        $this->channelId = $channelId;
        $this->itemString = json_encode($item);
        $this->disableDownload = $disableDownload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $item = json_decode($this->itemString);
        $channelId = $this->channelId;

        $videoId = preg_replace('/yt:video:/', '', $item->id);
        $entity = Entity::firstOrNew(['video_id' => $videoId]);
        if ($entity->id == null) {
            Log::info(" New Entry: " . $item->title);
        }
        $entity->channel_id = $channelId;
        $entity->title = $item->title;
        $entity->published = Carbon::parse($item->published)->addHours(8);
        $entity->updated = Carbon::parse($item->published)->addHours(8);
        $this->setThumbnail($entity, $item);
        $entity->thumbnail_source = $this->getThumbnail($item);
        if (is_array($item->media_group->media_description))
            $entity->description = join("<br/>\n", ($item->media_group->media_description));
        else
            $entity->description = (string)($item->media_group->media_description);
        $starRating = $this->getAttributes($item->media_group->media_community->media_starRating);
        $statistics = $this->getAttributes($item->media_group->media_community->media_statistics);
        $entity->views_count = $statistics->views;
        $entity->rating_count = $starRating->count;
        $entity->rating_average = $starRating->average;
        if ($this->isLive() == false) {
            $this->getSourceDurationAndIgnoreShort($entity);
        }
        if ($this->disableDownload == true) {
            $entity->video_uri = "null";
        }
        try {
            $entity->save();
        } catch (\Exception $e) {
            # dd($entity);
        }
        VideoDownloadJob::dispatch($entity->id);
    }

    private function isLive() {
//        $cmd = 'youtube-dl -o "%(is_live)s" --get-filename https://www.youtube.com/watch?v='
//            .$this->entity->video_id;
        $cmd = 'yt-dlp -o "%(is_live)s" --get-filename https://www.youtube.com/watch?v='
            .$this->entity->video_id;
        Log::info($cmd);
        exec($cmd, $output);
        Log::info($output);
        if (isset($output[0]) && $output[0] == 'True') return true;
        return false;
    }

    private function getSourceDurationAndIgnoreShort(Entity $entity) {
//        $cmd = 'youtube-dl -o "%(duration)s" --get-filename https://www.youtube.com/watch?v='
//            .$this->entity->video_id;
        $cmd = 'yt-dlp -o "%(duration)s" --get-filename https://www.youtube.com/watch?v='
            .$entity->video_id;
        Log::info($cmd);
        exec($cmd, $output);
        Log::info($output);

        if (isset($output[0])) {
            $entity->source_duration = $output[0];

            if ((int)$entity->source_duration < 200) {
                $entity->ignore = 1;
            }
            $entity->save();
        }
    }

    private function setThumbnail(&$entity, $item)
    {
        if ($this->getThumbnail($item) != $entity->thumbnail_source) {
            $entity->thumbnail = ImageFile::setPath($this->getThumbnail($item))
                ->resize("160")
                ->saveToStorage();
        }
    }

    private function getThumbnail($item)
    {
        return $this->getAttributes($item->media_group->media_thumbnail)->url;
    }

    private function getAttributes($attributesParentItem)
    {
        $keyArray = (array)$attributesParentItem;
        return $keyArray['@attributes'];
    }
}
