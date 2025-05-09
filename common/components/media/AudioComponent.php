<?php
namespace common\components\media;

use yii\base\Component;
use FFMpeg;


/**
 * Description of AudioComponent
 */
class AudioComponent extends Component
{
    public $pathToFFMPEG;
    public $pathToFFPROBE;

    /**
     * @var FFMpeg\FFProbe
     */
    private $_ffprobe;

    public function init() {
        $this->_ffprobe = FFMpeg\FFProbe::create([
            'ffmpeg.binaries'  => $this->pathToFFMPEG,
            'ffprobe.binaries' => $this->pathToFFPROBE
        ]);
    }

    /**
     * @param $url
     * @return mixed
     */
    public function getAudioDuration($url)
    {
        $fullSourceVideoPath = \Yii::getAlias('@frontendWeb') . '/' . $url;
        $duration = $this->_ffprobe
            ->format($fullSourceVideoPath)
            ->get('duration');

        return $duration;
    }
}