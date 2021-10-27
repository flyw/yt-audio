<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function showQueueLogs(Request $request) {
        $disableSource =false;
        if($request->has('disableSource')) {
            $disableSource = true;
        }
        $outputJson['progress'] = 0;
        $outputJson['eta'] = 0;
        $outputJson['fileSize'] = 'NA';
        exec("tail -n 300 ".storage_path("logs/laravel.queue.work.log"), $output);
//        if ($this->isFinished($output)) {
//            $outputJson['progress'] = 100;
//            $outputJson['eta'] = 0;
//            $outputJson['source'] = array_slice(array_reverse($output), 0, 50);
//            $outputJson['fileSize'] = 'NA';
//        }
//        else {
            $reverseOutput = array_reverse($output);
            $outputJson = array_merge($outputJson, $this->getProgress($reverseOutput));
            if (!$disableSource)
                $outputJson['source'] = array_slice(array_reverse($output), 0, 50);
//        }

        return $outputJson;
    }
    private function getProgress($runResults) {
        foreach ($runResults as $runResult) {
            if (preg_match("/ETA:/", $runResult, $match)) {
                preg_match("/\(\d+%\)/", $runResult, $progress);
                $progress = preg_replace("/\(/", "", $progress[0]);
                $progress = preg_replace("/%\)/", "", $progress);
                $outputJson['progress'] = $progress;
                preg_match("/ETA:\w+]$/", $runResult, $eta);
                $eta = preg_replace("/ETA:/", "", $eta[0]);
                $eta = preg_replace("/]/", "", $eta);
                $outputJson['eta'] = $eta;
                $size = preg_replace("/^\S+/", "", $runResult);
                $size = preg_replace("/\(.*?$/", "", $size);
                $outputJson['fileSize'] = trim($size);
                return $outputJson;
            }
        }
        return [];
    }
    private function isFinished($runResults) {
        foreach ($runResults as $runResult) {
            if ($runResult == '(OK):download completed.') return true;
        }
        return false;
    }
}
