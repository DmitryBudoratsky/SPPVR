<?php

namespace common\components\helpers;

use common\models\db\File;
use hosanna\audiojs\AudioJs;
use yii\helpers\Html;
use yii\helpers\Url;

class FileHelper
{
    /**
     * @param File $file
     * @return null|string
     * @throws \Exception
     */
    public static function prepareFileContent(File $file)
    {
        if ($file->isFileVideo()) {
            return \kato\VideojsWidget::widget([
                'options' => [
                    'class' => 'video-js vjs-default-skin vjs-big-play-centered',
                    'controls' => true,
                    'preload' => 'auto',
                    'width' => File::DISPLAY_VIDEO_WIDTH,
                    'height' => File::DISPLAY_VIDEO_HEIGHT,
                    'data-setup' => '{ "plugins" : { "resolutionSelector" : { "default_res" : "720" } } }',
                ],
                'tags' => [
                    'source' => [
                        ['src' => $file->getAbsoluteFileUrl() , 'type' => $file->mimeType, 'data-res' => '360'],
                    ],
                ],
                'multipleResolutions' => true,
            ]);
        } else if ($file->isFileImage()) {
            return Html::img($file->getAbsoluteFileUrl(), ['alt' => 'icon', 'class' => 'image']);
        } else if ($file->isFileAudio()) {
            // don't use absolute path
            $path = "../../" . $file->url;
            return AudioJs::widget([
                'files'=> $path, //Full URL to Mp3 file here or array of urls
            ]);
        }

        return null;
    }
}