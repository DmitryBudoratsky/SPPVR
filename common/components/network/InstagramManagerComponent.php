<?php


namespace common\components\network;


use common\components\CurlComponent;
use yii\base\Component;
use yii\helpers\Json;

class InstagramManagerComponent extends Component
{
    private static $_userInfoResponse;


    /**
     * @param $instagramAccessToken
     * @param $instagramUserId
     * @return array|null
     */
    public static function getUserInfo($instagramAccessToken, $instagramUserId)
    {
        if (self::validateAccessToken($instagramAccessToken, $instagramUserId)) {
            return self::serializeResponseToArray();
        }
        return [];
    }

    /**
     * @param $instagramAccessToken
     * @param $instagramUserId
     * @return bool
     */
    private static function validateAccessToken($instagramAccessToken, $instagramUserId)
    {
        $rawResponse = CurlComponent::sendJsonRequest("https://graph.instagram.com/". $instagramUserId . "?fields=id,username&access_token=" . $instagramAccessToken);

        $appInfoResponse = Json::decode($rawResponse);

        \Yii::trace('App info response: ' . var_export($appInfoResponse, true));

        if (isset($appInfoResponse['id'], $appInfoResponse['username'])) {
            self::$_userInfoResponse = $appInfoResponse;

            return true;
        }

        return false;
    }

    private static function serializeResponseToArray()
    {
        if (empty(self::$_userInfoResponse)) {
            return null;
        }

        $data = self::$_userInfoResponse;

        $resultArr = [];
        $resultArr['instagramUserId'] = $data['id'];
        $resultArr['name'] = $data['username'];

        if (isset($data['profile_picture'])) {
            $resultArr['pictureUrl'] = $data['profile_picture'];
        }

        return $resultArr;
    }
}