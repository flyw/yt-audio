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

class VideoManager implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    var $fileName = null;
    var $entityId = null;
    var $entity = null;
    /**
     * Create a new job instance.
     *
     * @param int $entityId
     */
    public function __construct(int $entityId)
    {
        $this->entityId = $entityId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
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
            $this->entity->viewd_index = null;
            $this->entity->video_uri = "null";
            $this->entity->save();
            $this->download();
            sleep(1);
            $this->updateDuration();
            sleep(1);
            $this->encode();
            sleep(1);
            \Log::info('Save entity.');
            $this->entity->save();
            \Log::info('Sleep 10 sec.');
            sleep(10);
        }
        else {
            \Log::info('Skip download: '.$this->entity->video_uri);
        }
    }

    private function isLive() {
        $cmd = 'youtube-dl -o "%(is_live)s" --get-filename https://www.youtube.com/watch?v='
            .$this->entity->video_id;
        Log::info($cmd);
        exec($cmd, $output);
        Log::info($output);
        if (isset($output[0]) && $output[0] == 'True') return true;
        return false;
    }
    private function download() {
        Log::info('Video Download... ');
        @unlink("/tmp/audio.webm");
        $cmd = 'youtube-dl -f "worstaudio" -o "/tmp/audio.webm" https://www.youtube.com/watch?v='
            .$this->entity->video_id .'  --external-downloader aria2c --external-downloader-args "-x 16"';
        Log::info($cmd);
        passthru($cmd);
//        Log::info($output);
//        $outputFile = $this->getDownloadedFilename($this->entity->video_id);
//        rename($outputFile, "/tmp/YT".$this->entity->video_id);
    }

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
        $cmd = "ffprobe -i /tmp/audio.webm -sexagesimal -show_format -v quiet | sed -n 's/duration=//p'";
        Log::info($cmd);
        exec($cmd, $output);
        Log::info($output);
        $time = @$output[0];
        $duration =  preg_replace('/^0:|\.\d+$/', "", $time);
        $this->entity->duration = $duration;
    }
    private function encode() {
        Log::info('Video Encoding... ');
        $start = microtime(true);
        $cmd = "ffmpeg -i /tmp/audio.webm -ac 1 -ar 24000 -b:a 16k /tmp/audio-new.m4a -y";
        Log::info($cmd);
        exec($cmd, $output);
        Log::info($output);
        Log::info('Video Encoded. ( '.(microtime(true)-$start).' sec )' );
        $sourceFileName = File::setPath("/tmp/audio-new.m4a")->saveToStorage();
        $this->entity->video_uri = $sourceFileName;
        unlink("/tmp/audio.webm");
        unlink("/tmp/audio-new.m4a");
    }
}
