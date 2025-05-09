<?php

namespace common\components\network;


use Yii;
use yii\base\Component;
use yii\helpers\Json;
use common\components\CurlComponent;
use common\models\db\Settings;

class FacebookManagerComponent extends Component
{
	private static $_userInfoResponse;
	
	/**
	 * @param integer $userId
	 * @param string $userToken
	 * @return array
	 */
	public static function getUserInfo($userId, $userToken)
	{
		if (self::validateAccessToken($userId, $userToken)) {
			return self::serializeResponseToArray();
		}
		
		return [];
	}
	
	/** 
	 * @param integer $userId
	 * @param string $userToken
	 * @return boolean
	 */
    public static function validateAccessToken($userId, $userToken)
    {
        $appInfoResponse = CurlComponent::sendJsonRequest("https://graph.facebook.com/app?access_token=" . $userToken);

        $userInfoResponse = CurlComponent::sendJsonRequest("https://graph.facebook.com/v2.5/me?fields=id,name,picture&access_token=" . $userToken);
        
        $appInfoResponse = Json::decode($appInfoResponse);
        $userInfoResponse = Json::decode($userInfoResponse);

        \Yii::trace('Empty app info response: ' . var_export(empty($appInfoResponse), true));
        \Yii::trace('Empty user info response: ' . var_export(empty($userInfoResponse), true));

        if (isset($appInfoResponse["id"]) && isset($userInfoResponse["id"])) {
        	$appId = (int)$appInfoResponse["id"];   	
        	$fbUserId = (int)$userInfoResponse["id"];
              	
        	if (empty($appInfoResponse) || ($appId != Yii::$app->params['faceBookAppId']) || ($fbUserId != $userId)) {
                \Yii::trace('AppId equal faceBookAppId: ' . var_export($appId == Yii::$app->params['faceBookAppId'], true));
                \Yii::trace('FbUserId equal UserId: ' . var_export($fbUserId == $userId, true));
        		return false;
        	}

        	self::$_userInfoResponse = $userInfoResponse;

        	return true;
        }

		return false;
    }
    
    /** Сюриализация данных для ответа
     * @param array $userInfoResponse
     * @return array
     */
    public static function serializeResponseToArray()
    {	
    	$userInfo = [];
    	$userInfo["name"] = self::$_userInfoResponse["name"];
    	   	
    	if (isset(self::$_userInfoResponse["picture"])) { 		
    		$userInfo["pictureUrl"] = self::$_userInfoResponse["picture"]["data"]["url"];
    	}
    	 		 
    	return $userInfo;
    }
}