<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDownloadRequest;
use App\Http\Requests\UpdateDownloadRequest;
use App\Jobs\YoutubeDownloadDeleteJob;
use App\Jobs\YoutubeDownloadJob;
use App\Repositories\DownloadRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Joydata\Utilities\Utils\File;
use Joydata\Utilities\Utils\ImageFile\ImageFile;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class DownloadController extends AppBaseController
{
    /** @var  DownloadRepository */
    private $downloadRepository;

    public function __construct(DownloadRepository $downloadRepo)
    {
        $this->downloadRepository = $downloadRepo;
    }

    /**
     * Display a listing of the Download.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->downloadRepository->pushCriteria(new RequestCriteria($request));
        $downloads = $this->downloadRepository->paginate(30);

        return view('downloads.index')
            ->with('downloads', $downloads);
    }

    /**
     * Show the form for creating a new Download.
     *
     * @return Response
     */
    public function create()
    {
        return view('downloads.create');
    }

    /**
     * Store a newly created Download in storage.
     *
     * @param CreateDownloadRequest $request
     *
     * @return Response
     */
    public function store(CreateDownloadRequest $request)
    {
        $input = $request->all();
        $info= parse_url($request->get('video_id'));
        if (preg_match("/list=/", $info['query'])) {
            $cmd = 'youtube-dl -j '.$request->get('video_id');
            Log::info($cmd);
            exec($cmd, $output);
//            Log::info($output);
//            dd(json_decode($output[0]));
//            $jsonObject = json_decode($output[0]);
            $this->createFromList($output);
            return redirect(route('downloads.index'));
        }
        else {
            $download = $this->downloadRepository->create($input);
            Flash::success('Download saved successfully.');
            return redirect(route('downloads.show', $download->id));
        }
    }

    private function createFromList($entries) {
        foreach ($entries as $entryJson) {
            $entry = json_decode($entryJson);
            $item['video_id'] = $entry->webpage_url;
            $this->downloadRepository->create($item);
        }
    }

    private function getThumbnail($youtubeUri) {
        $cmd = 'youtube-dl --list-thumbnail '.$youtubeUri;
        Log::info($cmd);
        exec($cmd, $output);
        Log::info($output);
//        dd($output[0]);
        $output = array_reverse($output);
        $output = collect(array_reverse($output))->filter(function ($item) {
            $matchResult = preg_match("/^\d+/", $item, $matches);
            if ($matchResult) {
                return $item;
            }
        })->toArray();
        $thumbnailLine = array_reverse($output)[0];
        $thumbnail = preg_replace("/^\d+\W+\d+\W+\d+\W+/", "", $thumbnailLine);
        $thumbnail = preg_replace("/\?.*?$/", "", $thumbnail);
//        dd($thumbnail);
        return File::setPath($thumbnail)->saveToStorage("downloads");
    }

    private function getTitle($youtubeUri) {
        $cmd = 'youtube-dl -j '.$youtubeUri;
        Log::info($cmd);
        exec($cmd, $output);
        Log::info($output);
        $jsonObject = json_decode($output[0]);
        return $jsonObject->title;
    }

    private function getAllFormat($youtubeUri) {
        $cmd = 'youtube-dl -F '.$youtubeUri;
        Log::info($cmd);
        exec($cmd, $output);
        Log::info($output);
        $output = collect(array_reverse($output))->filter(function ($item) {
            $matchResult = preg_match("/^\d+/", $item, $matches);
            if ($matchResult) {
                return $item;
            }
        })->toArray();
        $allFormats = [];
        foreach ($output as $item) {
            preg_match("/^\d+/", $item, $matches);
            $format = [];
            $format['code'] = $matches[0];
            $item = preg_replace("/^".$matches[0]."\W+/", "", $item);
            $format = $this->decodeFormatString($item, $format);
            $allFormats[] = $format;
        }
        return $allFormats;
    }

    private function decodeFormatString($formatString, $format) {
        preg_match("/^\w+/", $formatString, $matches);
        $format['extension'] = $matches[0];
        $formatString = preg_replace("/^".$matches[0]."\W+/", "", $formatString);
        if (preg_match("/audio only/", $formatString)) {
            $format = $this->decodeAudioString($formatString, $format);
        }
        else if (preg_match("/video only/", $formatString)) {
            $format = $this->decodeVideoString($formatString, $format);
        }
        else {
            $format = $this->decodeVAString($formatString, $format);
        }
        return $format;
    }

    private function decodeAudioString($formatString, $format) {
        $format['type'] = 'a';
        $formatString = trim(preg_replace('/audio only\W+/',"", $formatString));
        preg_match('/^\w.*?,/', $formatString, $resolution);
        $format['resolution'] = substr($resolution[0], 0, -2);
        preg_match('/\S+$/', $formatString, $fileSize);
        $format['fileSize'] = $fileSize[0];
        return $format;
    }

    private function decodeVideoString($formatString, $format) {
        $format['type'] = 'v';
        if (preg_match('/DASH video/', $formatString)) {
            preg_match('/^\S+/', $formatString, $resolution);
            $format['resolution'] = $resolution[0];
        }
        else {
            $formatString = trim(preg_replace('/^\d+x\d+/',"", $formatString));
            preg_match('/^\w+/', $formatString, $resolution);
            $format['resolution'] = $resolution[0];
        }
        $formatString = trim(preg_replace('/^.*,/', "", $formatString));
        if (preg_match('/\d+\S+$/', $formatString, $fileSize)) {
            $format['fileSize'] = $fileSize[0];
        }
        else {
            $format['fileSize'] = 'NAN';
        }
        return $format;
    }

    private function decodeVAString($formatString, $format) {
        $format['type'] = 'av';
        $formatString = trim(preg_replace('/^\d+x\d+/',"", $formatString));
        preg_match('/^\w+/', $formatString, $resolution);
        $format['resolution'] = $resolution[0];

        preg_match('/\S+$/', $formatString, $fileSize);
        $format['fileSize'] = $fileSize[0];
        return $format;
    }


    /**
     * Display the specified Download.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $download = $this->downloadRepository->findWithoutFail($id);
        $modified = false;
        if (!$download->available_format) {
            $allFormats = $this->getAllFormat($download->video_id);
            $download->available_format = json_encode($allFormats);
            $modified = true;
        }
        if (!$download->thumbnail_path) {
            $download->thumbnail_path = $this->getThumbnail($download->video_id);
            $modified = true;
        }
        if (!$download->title) {
            $download->title = $this->getTitle($download->video_id);
            $modified = true;
        }
        if ($modified) $download->save();
        $download->available_format_object = json_decode($download->available_format);

        if (empty($download)) {
            Flash::error('Download not found');

            return redirect(route('downloads.index'));
        }

        return view('downloads.show')->with('download', $download);
    }

    /**
     * Show the form for editing the specified Download.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $download = $this->downloadRepository->findWithoutFail($id);

        if (empty($download)) {
            Flash::error('Download not found');

            return redirect(route('downloads.index'));
        }

        return view('downloads.edit')->with('download', $download);
    }

    /**
     * Update the specified Download in storage.
     *
     * @param  int              $id
     * @param UpdateDownloadRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDownloadRequest $request)
    {
        $download = $this->downloadRepository->findWithoutFail($id);

        if (empty($download)) {
            Flash::error('Download not found');

            return redirect(route('downloads.index'));
        }

        $download = $this->downloadRepository->update($request->all(), $id);

        YoutubeDownloadJob::dispatch($download->id);

        Flash::success('Download updated successfully.');

        return redirect(route('downloads.index'));
    }

    /**
     * Remove the specified Download from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $download = $this->downloadRepository->findWithoutFail($id);

        if (empty($download)) {
            Flash::error('Download not found');

            return redirect(route('downloads.index'));
        }

//        $this->downloadRepository->delete($id);
        YoutubeDownloadDeleteJob::dispatch($id);

        Flash::success('Download deleted successfully.');

        return redirect(route('downloads.index'));
    }
}
