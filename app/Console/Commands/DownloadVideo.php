<?php

namespace App\Console\Commands;

use App\Jobs\VideoDownloadJob;
use App\Models\Entity;
use Illuminate\Console\Command;

class DownloadVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtube-dl:download {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $id = $this->option('id');
        if ($id == null) {
            $entities = Entity::where('video_uri', "=", 'null')
                ->orderBy('published', 'DESC')->get()->take(10);
            $table = [];
            foreach ($entities as $entity) {
                $table[] = [
                    $entity->id,
                    mb_substr($entity->title, 0 , 20),
                    optional($entity->channel)->name,
                    $entity->published
                ];
            }
            $this->table(['id', 'title', 'channel','published'],$table);
            $this->info('Use --id=? to download video.');
            exit;
        }

        $entity = Entity::find($id);
        $entity->video_uri = null;
        $entity->save();
        VideoDownloadJob::dispatchNow($id);
    }
}
