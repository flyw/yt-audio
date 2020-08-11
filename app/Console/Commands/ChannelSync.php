<?php

namespace App\Console\Commands;

use App\Jobs\EntryDownload;
use App\Models\Channel;
use App\Utils\FeedFetcher;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ChannelSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtube-dl:channel-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Channel';

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
        $channels = Channel::all();
        foreach ($channels as $channel) {
            $this->info('Sync Channel: '.$channel->name);
            $feed = FeedFetcher::fetch($channel->channel_id);
            $channel->published = Carbon::parse($feed->entry[0]->published)->addHours(8);
            foreach ($feed->entry as $entry) {
                $this->info('  Sync Entry: '.$entry->title);
                EntryDownload::dispatchNow($channel->id , $entry);
            }
        }
        $this->info('done');
    }
}
