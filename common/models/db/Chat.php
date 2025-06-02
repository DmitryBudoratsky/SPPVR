<?php

namespace common\models\db;


use common\components\CurlComponent;
use common\components\helpers\TimeHelper;
use common\components\neuro\GenApiClient;
use GenAPI\Client;

class Chat extends BaseChat
{
    public function sendToNeuroStartMessage(Incident $incident): bool
    {
        $text = 'Ты помощник врача-проффесионала, выдвини свои предположения и предложения для их оценки и вынесения окончательного вердикта врачом' . PHP_EOL;
        $years = (int)((time() - $incident->birthDate) / TimeHelper::getSecondsFromDays(365));
        $text .= 'Анамнез: ' . ($incident->sex == Incident::SEX_MAN ? 'Мужчина' : 'Женщина') . " $years лет;" . PHP_EOL;
        $text .= $incident->anamnesis;

        $response = self::sendToNeuro($text);
        if (is_null($response)) {
            return false;
        }

        $messageStart = new Message(['type' => Message::TYPE_START, 'text' => $text, 'chatId' => $this->chatId]);

        $messageNeuro = new Message(['type' => Message::TYPE_AI, 'text' => $response, 'chatId' => $this->chatId]);

        return $messageStart->save() && $messageNeuro->save();
    }

    public static function sendToNeuro($text)
    {
        $client = new GenApiClient();
        $client->setAuthToken(\Yii::$app->params['genApiKey']);

        $result = $client->createNetworkTask('grok-3', [
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $text
                ]
            ],
            'is_sync' => true
        ]);

        return $result['response'][0]['choices'][0]['message']['content'] ?? null;
    }
}
