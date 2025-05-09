<?php

namespace common\components\converters;

use yii\base\Component;
use FFMpeg;
use yii\helpers\FileHelper;
use FFMpeg\Filters\Frame\CustomFrameFilter;
use FFMpeg\Filters\Video\RotateFilter;
use FFMpeg\Filters\Audio\SimpleFilter;

/**
 * Description of VideoConverterComponent
 */
class VideoConverterComponent extends Component
{
    const VIDEO_FORMAT_CONVERT_EXTENSION = ".mp4";
    const VIDEO_FORMAT_CONVERT_MIME_TYPE = "video/mp4";

    public $pathToFFMPEG;
    public $pathToFFPROBE;
    public $videoMaxSize;
    public $videoTargetAspect;

    public $newWidth;
    public $newHeight;
    public $sourceWidth;
    public $sourceHeight;
    public $duration;
    public $rotationAngle;

    public $shouldAutoRotate = true;

    /**
     * @param $sourceVideoPath
     * @param $newFilename
     * @param $fileUploadDirPath
     * @param $previewImageFileUploadDirPath
     * @param $previewImageFilename
     * @return FFMpeg\Media\Audio|FFMpeg\Media\Video
     * @throws \yii\base\Exception
     */
    public function convertToMp4($sourceVideoPath, $newFilename, $fileUploadDirPath, $previewImageFileUploadDirPath, $previewImageFilename)
    {
        $ffmpeg = \FFMpeg\FFMpeg::create([
            'ffmpeg.binaries'  => $this->pathToFFMPEG,
            'ffprobe.binaries' => $this->pathToFFPROBE
        ]);
        \Yii::info('Source video URL: ' . $sourceVideoPath);

        $fullSourceVideoPath = \Yii::getAlias('@frontendWeb') . '/' . $sourceVideoPath;
        \Yii::info('Full source video URL: ' . $fullSourceVideoPath);

        $video = $ffmpeg->open($fullSourceVideoPath);

        $this->prepareVideoOptions($fullSourceVideoPath);

        if (!file_exists($previewImageFileUploadDirPath)) {
            FileHelper::createDirectory($previewImageFileUploadDirPath);
        }

        $framerate = 30;
        $gop = $framerate * 2;

        $sourceWidth = $this->sourceWidth;
        $sourceHeight = $this->sourceHeight;
        if ($this->isPortrait()) {
            $sourceWidth = $this->sourceHeight;
            $sourceHeight = $this->sourceWidth;
        }
        $sourceAspect = $sourceWidth / $sourceHeight;
        $this->videoTargetAspect = $sourceAspect;

        $this->newWidth = min($this->videoMaxSize, $this->sourceWidth);
        $this->newHeight = intval($this->newWidth / $this->videoTargetAspect);
        $cropWidth = $this->newWidth;
        $cropHeight = $this->newHeight;
        if ($sourceAspect <= $this->videoTargetAspect) {
            $cropWidth = $sourceWidth;
            $cropHeight = ceil($cropWidth / $this->videoTargetAspect);
        } else if ($sourceAspect > $this->videoTargetAspect) {
            $cropHeight = $sourceHeight;
            $cropWidth = floor($cropHeight * $this->videoTargetAspect);
        }
        $realCropWidth = $cropWidth;
        $realCropHeight = $cropHeight;
        $realNewWidth = $this->newWidth;
        $realNewHeight = $this->newHeight;
        if ($this->isPortrait() && $this->shouldAutoRotate) {
            $realCropWidth = $cropHeight;
            $realCropHeight = $cropWidth;
            $realNewWidth = $this->newHeight;
            $realNewHeight = $this->newWidth;
        }
        if (($realNewHeight % 2) != 0) {
            //Avoiding ffmpeg error, that height must be even
            $realNewHeight--;
        }
        \Yii::info("{$this->newWidth}x{$this->newHeight}, {$sourceAspect}, {$this->videoTargetAspect}, {$cropWidth}x${cropHeight}, {$realCropWidth}x{$realCropHeight}");
        $video->filters()
            ->framerate(new FFMpeg\Coordinate\FrameRate($framerate), $gop)
            ->synchronize();

        $cropX = ($this->sourceWidth - $realCropWidth) / 2;
        $cropY = ($this->sourceHeight - $realCropHeight) / 2;
        $video->addFilter(new SimpleFilter(array("-filter:v", "crop={$realCropWidth}:{$realCropHeight}:{$cropX}:{$cropY},scale={$realNewWidth}:{$realNewHeight}")));
        $video->addFilter(new SimpleFilter(array("-preset", "medium")));
        $video->addFilter(new SimpleFilter(array("-crf", "23")));
        $video->addFilter(new SimpleFilter(array("-tune", "zerolatency")));
        $video->addFilter(new SimpleFilter(array("-movflags", "+faststart")));
        $video->addFilter(new SimpleFilter(array("-strict", "-2")));

        if (!file_exists($fileUploadDirPath)) {
            FileHelper::createDirectory($fileUploadDirPath);
        }

        $format = new \FFMpeg\Format\Video\X264('aac', 'libx264');
        $format
            //->setKiloBitrate(1000)
            ->setAudioChannels(2)
            ->setAudioKiloBitrate(128);

        $videoPath = $fileUploadDirPath . '/' . $newFilename;
        \Yii::info('Video path: ' . $videoPath);

        $finalCommand = $video->getFinalCommand($format, $videoPath);
        \Yii::info('Final command: ' . var_export($finalCommand, true));
       
        $videoProcessingResult = $video->save($format, $videoPath);
        if ($videoProcessingResult) {
            $this->generatePreview($ffmpeg, $videoPath, $previewImageFileUploadDirPath, $previewImageFilename);
        }
        return $videoProcessingResult;
    }

    /**
     *
     */
    private function generatePreview($ffmpeg, $videoPath, $previewImageFileUploadDirPath, $previewImageFilename)
    {
        $video = $ffmpeg->open($videoPath);
        if (!file_exists($previewImageFileUploadDirPath)) {
            FileHelper::createDirectory($previewImageFileUploadDirPath);
        }

        $previewImagePath = $previewImageFileUploadDirPath . '/' . $previewImageFilename;
        \Yii::info('Preview image path: ' . $previewImagePath);

        $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(0));
        if (!empty($this->rotationAngle)) {
            $frame = $frame->addFilter(
                new CustomFrameFilter($this->getRotation())
            );
        }
        return $frame->save($previewImagePath);
    }

    /**
     * @return string
     */
    private function getRotation()
    {
        $rotation = null;
        $rotations = [
            '90' => RotateFilter::ROTATE_90,
            '180' => RotateFilter::ROTATE_180,
            '270' => RotateFilter::ROTATE_270,
        ];
        $rotation = isset($rotations[$this->rotationAngle]) ? $rotations[$this->rotationAngle] : null;
        \Yii::info('Rotation: ' . var_export($rotation, true));
        return $rotation;
    }

    /**
     * @param $fullSourceVideoPath
     */
    private function prepareVideoOptions($fullSourceVideoPath)
    {
        $ffprobe = FFMpeg\FFProbe::create([
            'ffmpeg.binaries'  => $this->pathToFFMPEG,
            'ffprobe.binaries' => $this->pathToFFPROBE
        ]);

        $format = $ffprobe->format($fullSourceVideoPath);
        $this->duration = $format->get('duration');
        \Yii::info('Video format: ' . var_export($format, true));

        $videoStream = $ffprobe
            ->streams($fullSourceVideoPath)
            ->videos()
            ->first();

        $width = $videoStream->get('width');
        $height = $videoStream->get('height');

        $dimension = $videoStream->getDimensions();
        \Yii::info('Video stream: ' . var_export($videoStream, true));

        $tags = $videoStream->get('tags');
        \Yii::info('Video stream tags: ' . var_export($tags, true));
        $this->rotationAngle = isset($tags['rotate']) ? $tags['rotate'] : 0;
        \Yii::info('Source video rotation angle: ' . $this->rotationAngle);

        $this->sourceWidth = !empty($width) ? $width : $dimension->getWidth();
        $this->sourceHeight = !empty($height) ? $height : $dimension->getHeight();
        \Yii::info('Source video size: ' . $this->sourceWidth . 'x' . $this->sourceHeight
            . ', dimenstion: ' . $dimension->getWidth() . 'x' . $dimension->getHeight());
    }

    /**
     * @return bool
     */
    public function isPortrait()
    {
        $isPortrait = in_array($this->rotationAngle, ['90', '270']);
        \Yii::info('isPortrait: ' . $isPortrait);
        return $isPortrait;
    }
}
