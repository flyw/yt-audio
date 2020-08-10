<?php


namespace App\Utils;


use Illuminate\Support\Facades\Cache;

class FeedFetcher
{
    static public function fetch($channelId) {
//        Cache::forget('channel-id-'.$channelId);
        $response = Cache::remember('channel-id-'.$channelId, 600, function () use ($channelId) {
            $url = "https://www.youtube.com/feeds/videos.xml?channel_id=".$channelId;
            $xmlString = preg_replace('/media:/', 'media_', file_get_contents($url));
            $xml =  simplexml_load_string($xmlString);
            return json_encode($xml, JSON_PRETTY_PRINT);
        });

        $array = json_decode($response,TRUE);

        return json_decode(json_encode($array));
    }

}
