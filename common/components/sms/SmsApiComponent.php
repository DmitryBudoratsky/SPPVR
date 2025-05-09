<?php

namespace common\components\sms;

use common\components\helpers\TransliterateHelper;
use yii\base\Component;
use common\components\CurlComponent;
use common\models\db\SendedSms;
use common\components\helpers\PhoneHelper;
use yii\helpers\Json;

/** Description of smsApi */
class SmsApiComponent extends Component
{
    const SUCCESS_STATUS = "OK";

    // sms.ru
    public $smsRuApiId;
    public $smsApiUrl;

    private $_response;


    /** Отправить смс
     * @param string $phone
     * @param string $text
     * @return void
     */
    public function send(string $phone, string $text)
    {
        $isRusNumber = PhoneHelper::checkPhoneToRusNumber($phone);

        if ($isRusNumber) {
            \Yii::trace('Sms sending via SMS.ru');
            $text = TransliterateHelper::transliterate($text);
            $this->sendSmsToRusNumber($phone, $text);
        } else {
            \Yii::trace('This phone number is not russian.');
            $this->_response['message'] = 'Указанный телефон не подходит для отправки СМС';
            return $this->_response;
        }

        $message = $this->_response['message'] ?? '';
        SendedSms::saveSendedSms($phone, $text, $message);
        \Yii::trace('Sms sending response: ' . var_export($this->_response, true));
        return $this->_response;
    }

    /**
     * Отправка смс на русский номер
     * @param string $phone
     * @param string $text
     */
    private function sendSmsToRusNumber(string $phone, string $text)
    {
        $fields = [
            "api_id" => $this->smsRuApiId,
            "to" => $phone,
            "text" => $text,
            "json" => 1
        ];

        $requestResult = CurlComponent::sendRequest($this->smsApiUrl, $fields);
        \Yii::trace('Sms server response: ' . var_export($requestResult, true));
        if (empty($requestResult)) {
            $this->_response = [
                'message' => "СМС сервис не отвечает",
            ];
            return;
        }

        try {
            $_result = Json::decode($requestResult, true);
        } catch (\Exception $e) {
            $this->_response = [
                'message' => "Не удалось разобрать формат ответа СМС сервиса",
            ];
            return;
        }

        $status = $_result["status"] ?? null;
        $statusText = $_result["status_text"] ?? "";

        if ($status != self::SUCCESS_STATUS) {
            $this->_response = [
                'statusCode' => $status,
                'message' => "Запрос не выполнен СМС сервисом. $statusText",
            ];
        }

        $smsArray = $_result["sms"] ?? [];

        foreach ($smsArray as $phone => $data) {
            $smsStatus = $data["status"] ?? null;
            $smsStatusCode = $data["status_code"] ?? null;
            $smsId = $data["sms_id"] ?? null;
            $smsStatusText = $data["status_text"] ?? null;
            if ($smsStatus == self::SUCCESS_STATUS) {
                $this->_response = [
                    'statusCode' => $smsStatusCode,
                    'message' => "Сообщение на номер $phone успешно отправлено. ID сообщения: $smsId",
                    'status' => self::SUCCESS_STATUS,
                ];
            } else {
                $this->_response = [
                    'statusCode' => $smsStatusCode,
                    'message' => "Сообщение на номер $phone не отправлено. $smsStatusText",
                ];
            }
        }
    }
}
