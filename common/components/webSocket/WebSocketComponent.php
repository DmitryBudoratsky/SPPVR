<?php

namespace common\components\webSocket;

use yii\helpers\Json;
use common\components\Yii;

class WebSocketComponent extends \morozovsk\yii2websocket\Connection
{
	
	/**
	 * @param integer $userId
	 * @param array $notificaitonInfo
	 * @param string $type
	 */
	public static function sendNotification($userId, $notificaitonInfo, $type)
	{
		\Yii::trace('Sending socket: ' . $userId . ', ' . var_export($notificaitonInfo, true) . ', ' . $type);
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
			Yii::getWebSocketComponent()->send($jsonMessage, 'personal-socket-server');
		} catch (\Exception $e) {
			$error = @var_export($e, true);
			\Yii::error($error, 'websocket');
		}
	}
	
	/**
	 * {@inheritDoc}
	 * @see \morozovsk\yii2websocket\Connection::send()
	 */
	public function send($message, $server = null) {
		if (!$server) {
			reset($this->servers);
			$server = key($this->servers);
		}
		$instance = $this->getInstance($server);
		if (empty($instance)) {
			return false;
		}
		return fwrite($this->getInstance($server), $message . "\n");
	}
	
	/**
	 * {@inheritDoc}
	 * @see \morozovsk\yii2websocket\Connection::getInstance()
	 */
    public function getInstance($server) {
        if (!isset($this->_instances[$server])) {
            $this->_instances[$server] = @stream_socket_client ($this->servers[$server]['localsocket'], $errno, $errstr);
        }
        return $this->_instances[$server];
    }
}
