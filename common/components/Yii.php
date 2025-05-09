<?php

namespace common\components;

use common\components\converters\VideoConverterComponent;
use common\components\media\AudioComponent;
use common\components\pushNotification\PushComponent;
use common\components\sms\SmsApiComponent;
use common\components\webSocket\WebSocketComponent;
use common\components\formatters\CurrencyFormater;

class Yii extends \Yii
{
	/** Возвращает PushComponent
	 * @return PushComponent
	 */
	public static function getPushComponent()
	{
		return self::$app->push;
	}
	
	/** Возвращает SmsApiComponent
	 * @return SmsApiComponent
	 */
	public static function getSmsComponent()
	{
		return self::$app->smsApi;
	}
	
	/** Возвращает WebSocketComponent
	 * @return WebSocketComponent
	 */
	public static function getWebSocketComponent()
	{
		return self::$app->websocket;
	}

    /** Возвращает WebSocketComponent
     * @return WebSocketComponent
     */
    public static function getRatchetSocketComponent()
    {
        return self::$app->ratchet;
    }
    
    /** Возвращает CurrencyFormater
     * @return CurrencyFormater
     */
    public static function getCurrencyFormater()
    {
    	return self::$app->currencyFormater;
    }

    /** Возвращает VideoConverterComponent
     * @return VideoConverterComponent
     */
    public static function getVideoConverterComponent()
    {
        return self::$app->videoConverter;
    }

    /** Возвращает AudioComponent
     * @return AudioComponent
     */
    public static function getAudioComponent()
    {
        return self::$app->audio;
    }
}