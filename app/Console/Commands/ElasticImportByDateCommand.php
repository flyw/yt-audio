<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Joydata\DeviceOperation\Models\PassRecord;

class ElasticImportByDateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'joydata:elastic:by-date-import {model} {start}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mysql\'s records import to Es by date';


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
        //
        set_time_limit(0);
        $model = $this->argument('model');
        $start = $this->argument('start');
        $dbmodel = app($model);
        $startDate = Carbon::createFromTimestamp(strtotime($start));
        $firstModel = $dbmodel->where("created_at", ">=", $startDate->format('Y-m-d H:i:s'))->first();
        $offset = $firstModel->id;
        Log::info("offset:".$offset);
        try {
            $dbmodel->where("id" ,">", $offset)
                ->chunk(2000, function ($passRecords) {
                    foreach ($passRecords as $item) {
                        $item->searchable();
                    }
                    Log::info("ElasticImportByDateCommand memory usages: ".$this->convert(memory_get_usage(true))); // 123 kb
                    unset($passRecords);
                    sleep(1);
                });
        } catch (\Exception $e) {
            Log::error($e->getTraceAsString());
        }
    }

    function convert($size)
    {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
}
