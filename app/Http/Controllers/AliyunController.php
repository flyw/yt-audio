<?php

namespace App\Http\Controllers;

use App\Models\Download;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class AliyunController extends Controller
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
        $fileContent = file_get_contents("/opt/aliyundrive-uploader/config.json");
        $config = collect(json_decode($fileContent))->only('REFRESH_TOKEN', 'DRIVE_ID', 'FILE_PATH');


//        echo "<pre>";
        $downloads = Download::whereNotNull('path')->get();
//        foreach ($downloads as $download) {
//            var_dump($download->path);
//        }
//        dd($downloads);



        return view('aliyun.index')
            ->with('config', $config)
            ->with('downloads', $downloads);
    }


    public function store(Request $request)
    {
        $fileContent = file_get_contents("/opt/aliyundrive-uploader/config.json");
        $config = json_decode($fileContent);
        $config->REFRESH_TOKEN = $request->get('REFRESH_TOKEN');
        $config->DRIVE_ID = $request->get('DRIVE_ID');
        $config->FILE_PATH = $request->get('FILE_PATH');
        $configContent = json_encode($config, JSON_UNESCAPED_SLASHES);
        $configContent = preg_replace("/:/", ": ", $configContent);
        $configContent = preg_replace("/,/", ", ", $configContent);
        file_put_contents("/opt/aliyundrive-uploader/config.json", $configContent);
        Flash::success('Config saved successfully.');
        return redirect(route('aliyun.index'));
    }


}
