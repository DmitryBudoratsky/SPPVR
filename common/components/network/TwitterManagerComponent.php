<?php

namespace common\components\network;

use common\components\CurlComponent;
use ErrorException;
use Yii;
use yii\base\Component;
use yii\helpers\Json;
use common\models\db\Settings;

class TwitterManagerComponent extends Component
{
    public static function validateAccessToken($userId, $userToken, $userName, $tokenSecret)
    {
        $url = self::getTwitterUrl($userToken, $userName, $tokenSecret);

        $userInfoResponse = CurlComponent::sendJsonRequest($url);

        $userInfoResponse = Json::decode($userInfoResponse);

        $twitterUserId = $userInfoResponse["id_str"];

        if (!$userInfoResponse || ($twitterUserId != $userId)) {
            throw new ErrorException("Access Denied");
            return false;
        }
        
        return true;
    }


    public static function getTwitterUrl($userToken, $userName, $tokenSecret)
    {
        $nonce = md5(uniqid(rand(), true));
        $timeStamp = time();

        $oauth_base_text = "GET&";
        $oauth_base_text .= urlencode('https://api.twitter.com/1.1/account/verify_credentials.json').'&';
        $oauth_base_text .= urlencode('oauth_consumer_key='. Yii::$app->params['twitterConsumerKey'] .'&');
        $oauth_base_text .= urlencode('oauth_nonce='. $nonce .'&');
        $oauth_base_text .= urlencode('oauth_signature_method=HMAC-SHA1&');
        $oauth_base_text .= urlencode('oauth_timestamp='. $timeStamp ."&");
        $oauth_base_text .= urlencode('oauth_token='. $userToken ."&");
        $oauth_base_text .= urlencode('oauth_version=1.0&');
        $oauth_base_text .= urlencode('screen_name=' . $userName);

        $key = Yii::$app->params['twitterConsumerSecret'] . '&' . $tokenSecret;

        $signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

        $url = 'https://api.twitter.com/1.1/account/verify_credentials.json';
        $url .= '?oauth_consumer_key=' . Yii::$app->params['twitterConsumerKey'];
        $url .= '&oauth_nonce=' . $nonce;
        $url .= '&oauth_signature=' . urlencode($signature);
        $url .= '&oauth_signature_method=HMAC-SHA1';
        $url .= '&oauth_timestamp=' . $timeStamp;
        $url .= '&oauth_token=' . urlencode($userToken);
        $url .= '&oauth_version=1.0';
        $url .= '&screen_name=' . $userName;

        return $url;
    }
}