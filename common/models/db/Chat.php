<?php

namespace common\models\db;


use common\components\CurlComponent;
use common\components\helpers\TimeHelper;
use common\components\neuro\GenApiClient;
use GenAPI\Client;

class Chat extends BaseChat
{
    public function sendStartMessage(Incident $incident): bool
    {
        $years = (int)((time() - $incident->birthDate) / TimeHelper::getSecondsFromDays(365));
        $text = 'Анамнез: ' . ($incident->sex == Incident::SEX_MAN ? 'Мужчина' : 'Женщина') . " $years лет;" . PHP_EOL;
        $text .= $incident->anamnesis;

        $messageStart = new Message(['type' => Message::TYPE_START, 'text' => $text, 'chatId' => $this->chatId]);

        return $messageStart->save();
    }

    public function sendToNeuro()
    {
        $client = new GenApiClient();
        $client->setAuthToken(\Yii::$app->params['genApiKey']);
        $messages = [];

        $query = $this->getMessages()
            ->orderBy(['createdAt' => SORT_ASC]);

        foreach ($query->each() as /** @var Message $message */ $message) {
            $text = $message->text;
            if ($message->type == Message::TYPE_START) {
                $text = 'Ты помощник врача-проффесионала, выдвини свои предположения и предложения для их оценки и вынесения окончательного вердикта врачом. В последнем абзаце необходимо предоставить список с вероятными диагнозами ДО 4 ПУНКТОВ ПО УБЫВАНИЮ С ЧИСЛОМ % ВЕРОЯТНОСТИ ' . PHP_EOL . $text;
            }

            $messages[] = [
                'role' => $message->type != Message::TYPE_AI ? 'user' : 'assistant',
                'content' => $text . '(Отвечай очень кратко, в пределах 4 предложений + абзац с вероятными диагнозами)'
            ];
        }

        $result = $client->createNetworkTask('grok-3', [
            'messages' => $messages,
            'is_sync' => true
        ]);

        return $result['response'][0]['choices'][0]['message']['content'] ?? null;
    }
}
