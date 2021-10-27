<?php

namespace App\Jobs;

use App\Models\Download;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Joydata\Utilities\Utils\File;

class YoutubeDownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 21600; //6 hour

    var $downloadId = null;
    var $randomDirectory = null;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($downloadId)
    {
        $this->downloadId = $downloadId;
        $this->randomDirectory = md5(random_bytes(64));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $item = Download::find($this->downloadId);
        $this->download($item);
        $myfiles = array_diff(scandir("/tmp/".$this->randomDirectory), array('.', '..'));
        $myfiles = array_values($myfiles);
        $path = File::setPath("/tmp/".$this->randomDirectory.'/'.$myfiles[0])->saveToStorage("downloads");

        $item->path = $path;
        $item->save();

        unlink("/tmp/".$this->randomDirectory.'/'.$myfiles[0]);
        rmdir("/tmp/".$this->randomDirectory);
    }

    private function download($item) {

        Log::info('Video Download... ');
        $cmd = 'youtube-dl -f '.$item->selected_format.' -o "/tmp/'.$this->randomDirectory.'/%(id)s.%(ext)s" '.$item->video_id.'  --external-downloader aria2c --external-downloader-args "-x 16"';
        Log::info($cmd);
        passthru($cmd);
//        Log::info($output);
//        $outputFile = $this->getDownloadedFilename($this->entity->video_id);
//        rename($outputFile, "/tmp/YT".$this->entity->video_id);
    }
}
