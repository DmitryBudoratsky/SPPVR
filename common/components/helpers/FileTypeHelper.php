<?php

namespace common\components\helpers;


class FileTypeHelper
{
	const TYPE_IMAGE = "image";
	const TYPE_AUDIO = "audio";
	const TYPE_VIDEO = "video";
	const TYPE_DOCUMENT = "document";
	
	const TYPE_IMAGE_LABEL = "изображение";
	const TYPE_AUDIO_LABEL = "голосовое сообщение";
	const TYPE_VIDEO_LABEL = "видео сообщение";
	const TYPE_DOCUMENT_LABEL = "документ";
	
	/**
	 * @return string[]
	 */
	public static function getTypes()
	{
		return [
			self::TYPE_IMAGE => self::TYPE_IMAGE_LABEL,
			self::TYPE_AUDIO => self::TYPE_AUDIO_LABEL,
			self::TYPE_VIDEO => self::TYPE_VIDEO_LABEL,
			self::TYPE_DOCUMENT => self::TYPE_DOCUMENT_LABEL,
		];
	}
	
	/**
	 * @param integer $type
	 * @return string
	 */
	public static function getTypeLabel($type)
	{
		$types = self::getTypes();
		return isset($types[$type]) ? $types[$type] : '';
	}

    /**
     * @param $file
     * @return string
     */
    public static function getFileType($file)
    {
    	if (
            in_array($file->type, self::imageMimeTypes())
            || in_array($file->extension, self::imageExtensions())
            ) {
    		return self::TYPE_IMAGE;
    	}
    	
    	if (
            in_array($file->type, self::audioMimeTypes())
            || in_array($file->extension, self::audioExtensions())
            ) {
    		return self::TYPE_AUDIO;
    	}
    	
    	if (
    		in_array($file->type, self::videoMimeTypes())
    		|| in_array($file->extension, self::videoExtensions())
    		) {
    		return self::TYPE_VIDEO;
    	}
    	
    	return self::TYPE_DOCUMENT;
    }
    
    /**
     * @return array
     */
    public static function imageMimeTypes()
    {
    	return [
    		'image/png',
            'image/jpg',
    		'image/jpeg',
    		'image/pjpeg'
    	];
    }
    
    /**
     * @return array
     */
    public static function imageExtensions()
    {
    	return [
    		'jpg',
    		'jpeg',
    		'png',
    		'gif'
    	];
    }
    
    /**
     * @return array
     */
    public static function audioMimeTypes()
    {
    	return [
    		'audio/mpeg',
    		'audio/mpeg3',
    		'audio/x-mpeg',
    		'audio/midi',
    		'audio/x-mid',
    		'audio/x-midi',
    		'audio/3gpp',
            'audio/aac',
            'audio/m4a'
    	];
    }


    /**
     * @return array
     */
    public static function audioExtensions()
    {
        return [
            '3gpp',
            'mp3',
            'midi',
            'wav',
            'aac',
            'm4a'
        ];
    }
    
    /**
     * @return array
     */
    public static function videoMimeTypes()
    {
    	return [
    		'video/x-flv',
    		'video/mp4',
    		'video/MP2T',
    		'video/3gpp',
    		'video/quicktime',
    		'video/x-msvideo',
    		'video/x-ms-wmv',
            'video/x-m4v'
    	];
    }

    /**
     * @return array
     */
    public static function videoExtensions()
    {
    	return [
    		'flv',
    		'mp4',
    		'ts',
    		'3gp',
    		'mov',
    		'avi',
    		'wmv',
            '.m4v'
    	];
    }
}