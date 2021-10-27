<?php

namespace App\Jobs;

use App\Models\Download;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class YoutubeDownloadDeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    var $downloadId = null;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($downloadId)
    {
        $this->downloadId = $downloadId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $item = Download::find($this->downloadId);
        if (file_exists(storage_path("app/public/".$item->path)))
            unlink(storage_path("app/public/".$item->path));
        if (file_exists(storage_path("app/public/".$item->thumbnail_path)))
            unlink(storage_path("app/public/".$item->thumbnail_path));
        $item->delete();
    }
}
