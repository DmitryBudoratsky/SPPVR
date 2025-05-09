<?php

namespace common\components\pushNotification;

use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use yii\base\Component;
use yii\helpers\Json;
use common\models\db\User;
use common\models\db\PushNotification;
use common\models\db\PushToken;

class PushComponent extends Component
{
    public $commonTopic;

    /**
     * Отправка push уведомления
     * @param PushNotification $pushNotification
     * @return bool
     */
    public function sendPushNotification(PushNotification $pushNotification): bool
    {
        if (!empty($pushNotification->userId)) {
            if (!$this->sendPersonalNotification($pushNotification)) {
                return false;
            }
        } else {
            if (!$this->sendTopicNotification($pushNotification)) {
                return false;
            }
        }

        return $this->changeStatusToSendPushNotification($pushNotification);
    }

    /**
     * Отправка персонального push уведомления на устройства пользователя
     * @param PushNotification $pushNotification
     * @return boolean
     */
    private function sendPersonalNotification(PushNotification $pushNotification): bool
    {
        $user = User::findOne(['user.userId' => $pushNotification->userId]);
        if (empty($user)) {
            return false;
        }

        $tokenQuery = $user->getPushTokens()->groupBy('token')->orderBy(['createdAt' => SORT_ASC]);
        foreach ($tokenQuery->each() as /** @var PushToken $pushToken */ $pushToken) {
            $this->sendToFCM($pushNotification, $pushToken->token, false);
        }

        return true;
    }

    /**
     * Отправка push уведомлений по топику
     * @param PushNotification $pushNotification
     * @return bool
     */
    private function sendTopicNotification(PushNotification $pushNotification): bool
    {
        $topic = $pushNotification->topic;
        if (empty($topic)) {
            $topic = $this->commonTopic;
        }

        $this->sendToFCM($pushNotification, $topic);
        return true;
    }

    /**
     * Сохранение статуса отправки push уведомления
     * @param PushNotification $pushNotification
     * @return boolean
     * @throws
     */
    private function changeStatusToSendPushNotification(PushNotification $pushNotification): bool
    {
        $response = Json::decode($pushNotification->pushServerResponse);

        $pushNotification->status = !isset($response['error']) ? PushNotification::STATUS_SENDED : PushNotification::STATUS_SENDING_ERROR;

        return $pushNotification->save();
    }

    /**
     * Отправка уведомления через Firebase Cloud Messaging
     * @param PushNotification $pushNotification
     * @param string $to
     * @param bool $byTopic
     * @return void
     * @see https://firebase-php.readthedocs.io/en/7.12.0/cloud-messaging.html
     */
    public function sendToFCM(PushNotification $pushNotification, string $to, bool $byTopic = true): void
    {
        // https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages?hl=ru#androidconfig
        $androidConfig = AndroidConfig::fromArray([
            //'priority' => 'normal',
            'notification' => [
                //'sound' => '',
                'notification_count' => (int)$pushNotification->badge,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ],
        ]);

        // https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages?hl=ru#apnsconfig
        $iosConfig = ApnsConfig::fromArray([
            'headers' => [
                //'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'badge' => (int)$pushNotification->badge,
                    //'content-available' => 1,
                    'sound' => 'default',
                ],
            ],
        ]);

        $target = $byTopic ? 'topic' : 'token';
        $imageUrl = NULL;
        $notification = Notification::create($pushNotification->title, $pushNotification->message, $imageUrl);
        $data = $this->getConvertedData($pushNotification);

        $message = CloudMessage::withTarget($target, $to)
            ->withAndroidConfig($androidConfig)
            ->withApnsConfig($iosConfig)
            ->withNotification($notification)
            ->withData($data);

        $fcm = (new Factory)
            ->withServiceAccount('common/config/firebase.json')
            ->createMessaging();

        try {
            $response = $fcm->send($message);
            $pushNotification->pushServerResponse = json_encode($response);
        } catch (FirebaseException|MessagingException $e) {
            $pushNotification->pushServerResponse = json_encode($e->errors());
            echo $e->getMessage() . "\n";
        }
    }

    /**
     * Получение информации уведомления в виде одномерного массива
     * @param PushNotification $pushNotification
     * @return array
     */
    private function getConvertedData(PushNotification $pushNotification): array
    {
        $data = array_merge($pushNotification->getDataAsArray(), ['type' => $pushNotification->type]);
        $result = [];

        foreach ($data as $key => $value) {
            $result[$key] = is_array($value) ? json_encode($value) : $value;
        }

        return $result;
    }
}