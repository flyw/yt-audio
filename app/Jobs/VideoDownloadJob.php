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
use Joydata\Utilities\Utils\File;

class VideoDownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    var $fileName = null;
    var $entityId = null;
    var ?Entity $entity = null;
    var $randomSeed = "randomSeed";
    var $downloadRetry = 0;
    var $attempts = 1;

    public int $tries = 4;

    /**
     * Create a new job instance.
     *
     * @param int $entityId
     */
    public function __construct(int $entityId, $attempts = 1)
    {

        $this->entityId = $entityId;
        $this->randomSeed = md5($entityId);
        $this->attempts = $attempts;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info("attempts: " .$this->attempts);
        set_time_limit(0);
        $this->entity = Entity::find($this->entityId);
        $this->fileName = "/tmp/YT".$this->entity->video_id;
        if ($this->entity->video_uri == null) {
            if ($this->isLive()) {
                $this->entity->viewd_index = 1;
                $this->entity->save();
                \Log::info('Live video, retry download in 1 hour');
                return;
            }
            $this->getSourceDurationAndIgnoreShort($this->entity);
            if ($this->entity->ignore == 1) {
                Log::info("ignore exit, source: {$this->entity->source_duration}");
                return;
            }

            $this->entity->viewd_index = null;
            $this->entity->save();

            $this->clearDownloadPath();
            if($this->entity->source_duration == null) {
                Log::error("source_duration not fetched. " . $this->entity->toJson());
                return;
            }

            if (!$this->download()) {
                return;
            }
            sleep(1);
            $this->updateDuration();
            sleep(1);
            $this->encode();
            sleep(1);
            \Log::info('Save entity.');
            $this->entity->save();
            \Log::info('Sleep 60 sec.');
            sleep(60);
        }
    }

    private function getSourceDurationAndIgnoreShort(Entity $entity) {
//        $cmd = 'youtube-dl -o "%(duration)s" --get-filename https://www.youtube.com/watch?v='
//            .$this->entity->video_id;
        $cmd = 'yt-dlp -o "%(duration)s" --get-filename https://www.youtube.com/watch?v='
            .$this->entity->video_id;
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

    private function clearDownloadPath() {
        Log::info('Video Download... ');
        exec("rm -rf /tmp/$this->randomSeed");
        mkdir("/tmp/".$this->randomSeed, 0777);
    }
    /**
     * @throws \Exception
     */
    private function download(): bool
    {
        try {
            return $this->doDownload();
        }
        catch (\Exception $e) {
            if ($this->downloadRetry++ < 3) {
                Log::info("Video Download retry: $this->downloadRetry times.");
                return $this->download();
            }
            else {
                throw $e;
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function doDownload(): bool
    {
//        @unlink("/tmp/audio.webm");
        $cmd = 'youtube-dl -f "worstaudio" -o "/tmp/'.$this->randomSeed.'/'.$this->randomSeed.'.webm" https://www.youtube.com/watch?v='
            .$this->entity->video_id .'  --external-downloader aria2c --external-downloader-args "-x 16 -s 16 -k 1M"  2>&1';

        $timeout = ($this->entity->source_duration + 1);
        $cmd = 'yt-dlp -f "wa" -o "/tmp/'.$this->randomSeed.'/'.$this->randomSeed.'.webm" "https://www.youtube.com/watch?v=' .$this->entity->video_id .'" '
            ." --socket-timeout $timeout 2>&1";
        Log::info($cmd);
        exec($cmd, $output);
        foreach ($output as $outputLine) {
            if (str_contains($outputLine, "Video unavailable")) {
                $this->entity->forceDelete();
                return false;
            }
        }
        if (file_exists("/tmp/$this->randomSeed/$this->randomSeed.webm") == false) {
            throw new \Exception("File Download Failed. Entity: ".$this->entity->title);
        }
        return true;

    }

    /**
     * @throws \Exception
     */
    private function getDownloadedFilename($videoId) {
        $dir = "/tmp";
        $files = scandir($dir);

        foreach($files as $key => $filename){
            if(preg_match("/YT$videoId/", $filename)){
                Log::info('Search File: '.realpath($dir.DIRECTORY_SEPARATOR.$filename));
                return realpath($dir.DIRECTORY_SEPARATOR.$filename);
            }
        }
        $this->entity->video_uri = "null";
        $this->entity->save();
        throw new \Exception('Cannot find file.');
    }
    private function updateDuration() {
        Log::info('Video get Duration... ');
        $cmd = "ffprobe -i /tmp/$this->randomSeed/$this->randomSeed.webm -sexagesimal -show_format -v quiet | sed -n 's/duration=//p'";
        Log::info($cmd);
        exec($cmd, $output);
        Log::info($output);
        $time = @$output[0];
        $duration =  preg_replace('/^0:|\.\d+$/', "", $time);
        $this->entity->duration = $duration;
        $this->entity->is_viewed = 0;
    }
    private function encode() {
        Log::info('Video Encoding... ');
        $start = microtime(true);
        $cmd = "ffmpeg -i /tmp/$this->randomSeed/$this->randomSeed.webm -ac 1 -ar 24000 -b:a 16k -f hls -hls_time 60 -hls_list_size 0 /tmp/$this->randomSeed/$this->randomSeed.m3u8 -y";
        Log::info($cmd);
        exec($cmd, $output);
        Log::info($output);
        Log::info('Video Encoded. ( '.(microtime(true)-$start).' sec )' );
        $file = File::setPath("/tmp/$this->randomSeed/$this->randomSeed.m3u8");
        $sourceFileName = $file->saveToStorage();
        exec("rm ".$file->getTempPath());
        $storagePath = pathinfo(storage_path("app/public/$sourceFileName"))['dirname'];
        exec("cp /tmp/$this->randomSeed/$this->randomSeed*ts $storagePath");
//        dd($storagePath, storage_path("app/public/$sourceFileName"));
        $this->entity->video_uri = $sourceFileName;
        exec("rm -rf /tmp/$this->randomSeed");
//        unlink("/tmp/audio-new.m4a");
    }

    public function failed($exception)
    {

        Log::debug("VideoDownloadJob: onfailed: {$this->attempts()}");
        if ($this->attempts() <= 4) {
            // hard fail in first 4 attempts (30, 60, 120, 240)
            Log::debug("VideoDownloadJob: re-attempts: {$this->attempts()}");
            if ($this->entity->ignore == 0)  {
                VideoDownloadJob::dispatch($this->entity->id)->delay(15 * ($this->attempts() + 1)* ($this->attempts() + 1));
            }
        }
        else {
            Log::debug("VideoDownloadJob: re-attempts: max.");
        }
    }

}
