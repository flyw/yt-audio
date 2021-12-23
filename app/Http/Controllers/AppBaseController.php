<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use Response;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }

    function human_filesize($bytes, $decimals = 2) {
        if ($bytes == null) return "0b";
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    function getFileSize($filenameWithPath) {
        $pathInfo = @pathinfo($filenameWithPath);
        if (isset($pathInfo['extension']) && $pathInfo['extension'] == 'm3u8') {
            $dirname = $pathInfo['dirname'];
            $content = file_get_contents($filenameWithPath);
            $content = preg_replace("/\n/", " ", $content);
            preg_match("/, .*?ts/", $content, $match);
            $tsPrefix = substr($match[0], 2, -4);
            unset($content);
            $result = @exec("find $dirname -type f -name '$tsPrefix*' -exec du -cb {} + | grep total ");
            $result = preg_replace("/\t.*?$/", "", $result);
        }
        else {
            $result = @filesize($filenameWithPath);
        }

        return $result;
    }
}
