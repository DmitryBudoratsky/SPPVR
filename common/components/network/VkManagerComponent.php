<?php

namespace common\components\network;

use yii\base\Component;
use yii\helpers\Json;
use common\components\CurlComponent;

class VkManagerComponent extends Component
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
        $userInfoResponse = CurlComponent::sendJsonRequest("https://api.vk.com/method/users.get?v=5.74&fields=photo_50&access_token=" . $userToken);
        
        \Yii::trace("userInfoResponse: " . var_export($userInfoResponse, true));

        $userInfoResponse = Json::decode($userInfoResponse);
        
      	if (isset($userInfoResponse["response"])) {
      		$vkUserUid = $userInfoResponse["response"][0]["id"];
      		if ($vkUserUid != $userId) {
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
    	$userInfo["name"] = self::$_userInfoResponse["response"][0]["first_name"] . ' ' .
    						self::$_userInfoResponse["response"][0]["last_name"];
    	
    	if (isset(self::$_userInfoResponse["response"][0]["photo_50"])) {
    		$userInfo["pictureUrl"] = self::$_userInfoResponse["response"][0]["photo_50"];
    	}
    						   	
    	return $userInfo;
    }   
}