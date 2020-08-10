<?php


namespace App\Utils;


use App\Models\Entity;
use Joydata\Utilities\Utils\File;
use Joydata\Utilities\Utils\ImageFile\ImageFile;

class AudioManager
{
    private $entryId = null;
    /** @var null Entity */
    private $entity = null;
    private $filename = null;

    public function __construct(Entity &$entity)
    {
        $this->entity = $entity;
        $this->entryId = $entity->video_id;
        $this->filename = "/tmp/".$entity->video_id;
    }

    function download() {
        if ($this->filename == null) {
            throw new \Exception('filename is empty');
        }
        $cmd = 'youtube-dl -f "worstvideo+worstaudio" -o "/tmp/%(id)s" '.$this->entryId;
        exec($cmd, $output);

        $cmd = 'youtube-dl -f "worstaudio" -o "/tmp/'.$this->filename.'" '.$this->entryId;
        exec($cmd, $output);
        $sourceFileName = File::setPath("/tmp/".$this->filename)->saveToStorage();
        $this->entity->audio_file_uri = $sourceFileName;
        $this->entity->save();
        unlink("/tmp/".$this->filename);
        return $sourceFileName;
    }

    function encode() {
        $cmd = "ffmpeg -i ".$this->filename." -ac 1 -ar 22050 -r 0.2 -b:a 10k -b:v 2k ".$this->filename.".mp4 -y";
        exec($cmd, $output);
    }

    function getTime() {
        if ($this->entity->duration == null) {
            $cmd = "ffprobe -i ".$this->entryId." -sexagesimal -show_format -v quiet | sed -n 's/duration=//p'";
            exec($cmd, $output);
            $time = @$output[0];
            $time = preg_replace('/^0:|\..*?$/', "", $time);
            $this->entity->duration = $time;
            $this->entity->save();
        }
        return $this->entity->duration;
    }
}


// 1.5 min A4vpKv7WAzQ
// 11 min t4BCJo62puQ

//youtube-dl -f "worstvideo+worstaudio" -o "%(id)s.%(abr)s.%(ext)s"  A4vpKv7WAzQ
//ffmpeg -i A4vpKv7WAzQ.128.mp4 -ac 1 -ar 22050 -r 0.2 -b:a 10k -b:v 2k  out.mp4 -y

//ffprobe -i A4vpKv7WAzQ -sexagesimal -show_format -v quiet | sed -n 's/duration=//p'
