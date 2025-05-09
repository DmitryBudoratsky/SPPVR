<?php

namespace common\components\ratchet;

use yii\base\Component;
use yii\helpers\Json;
use common\components\Yii;
use ZMQContext;
use ZMQSocket;
use ZMQ;

class RatchetSocketComponent extends Component
{
	public $port;
	/**
	 * @param integer $userId
	 * @param array $notificaitonInfo
	 * @param string $type
	 */
	public static function sendNotification($userId, $notificaitonInfo, $type)
	{
		\Yii::trace('Sending socket: ' . $userId . ', ' . var_export($notificaitonInfo, true) . ', ', $type);
		$notificaitonInfo = [
			'userId' => $userId,
			'socketType' => $type,
			'data' => $notificaitonInfo,
		];
		self::sendNotificationToWebSockets($notificaitonInfo);
	}
	
	/** Отправка сообщений в web socket
	 * @param array $message
	 */
	private static function sendNotificationToWebSockets($message)
	{
		try {
			$jsonMessage = Json::encode($message);
			/** @var RatchetSocketComponent $component */
			$component = Yii::getRatchetSocketComponent();
			$component->send($jsonMessage);
		} catch (\Exception $e) {
			$error = @var_export($e, true);
			\Yii::error($error, 'websocket');
		}
	}

    /**
     * {@inheritDoc}
     * @see \morozovsk\yii2websocket\Connection::send()
     */
    public function send($message) {
        $context = new ZMQContext();

        $socket = $context->getSocket(ZMQ::SOCKET_REQ, 1);
        $socket->connect("tcp://localhost:8080", true);
        $socket->send($message);
    }
}
