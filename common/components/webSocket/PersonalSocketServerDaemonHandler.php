<?php

namespace common\components\webSocket;

use common\models\db\AccessToken;
use yii\helpers\Json;
use yii;

class PersonalSocketServerDaemonHandler extends \morozovsk\websocket\Daemon
{
	public $userIds = [];
	
	/**
	 * {@inheritDoc}
	 * @see \morozovsk\websocket\Daemon::onOpen()
	 */
	protected function onOpen($connectionId, $info)
	{
		echo "Trying to open connection #{$connectionId}, " . var_export($info, true) . "\n";
		echo "Info: " . var_export($info, true) . "\n";

		// вызывается при соединении с новым клиентом
		$paramsString = $info['GET'];
		$paramsString = $this->clearParamsString($paramsString);
		parse_str($paramsString, $_GET); // parse get-query

		echo "Incoming params" . var_export($_GET, true) . "\n";

		try {
			$accessTokenString = isset($_GET['accessToken']) ? $_GET['accessToken'] : null;
			echo "Connection #{$connectionId}. Access token: " . $accessTokenString . "\n";

			/**
			 *
			 * @var AccessToken $accessToken
			 */
			$accessToken = AccessToken::find()->andWhere(['token' => $accessTokenString])->one();
			if (empty($accessToken)) {
				echo "Connection #{$connectionId}. Empty access token\n";
				$this->close($connectionId);
				return;
			}
			$this->userIds[$connectionId] = $accessToken->userId;
			
			$connectedMessage = "WebSocket: User #{$accessToken->userId} connected to {$connectionId}.";
			echo "{$connectedMessage}\n";
			\Yii::info($connectedMessage, 'websocket');
		} catch (\yii\db\Exception $e) {
			echo "DB Exception: " . $e->getMessage() . "\n";
			$this->reopenDbConnection();
			$this->onOpen($connectionId, $info);
		} catch (\Exception $e) {
			$error = @var_export($e, true);
			echo "WebSocket Exception:\n";
			echo $error . "\n";
			\Yii::error($error, 'websocket');
			$this->close($connectionId);
		}
	}
	
	protected function _onMessage($connectionId)
	{
		if (isset($this->_read[$connectionId])) {
			var_dump('Change read');
			$this->_read[$connectionId] = str_replace('sec-websocket-key:', 'Sec-WebSocket-Key:', $this->_read[$connectionId]);
		}
        parent::_onMessage($connectionId);
    }

	/**
	 * @return string
	 */
	private function clearParamsString($paramsString)
	{
		if (!empty($paramsString) && $paramsString[0] == "/") {
			$paramsString = substr($paramsString, 1);
		}
		if (!empty($paramsString) && $paramsString[0] == "?") {
			$paramsString = substr($paramsString, 1);
		}
		return $paramsString;
	}

	/**
	 * Reopens DB connection.
	 */
	private function reopenDbConnection()
	{
		\Yii::$app->getDb()->close();
		gc_collect_cycles();
	}
	

	/**
	 * {@inheritDoc}
	 * @see \morozovsk\websocket\Daemon::onClose()
	 */
	protected function onClose($connectionId)
	{
		// вызывается при закрытии соединения с существующим клиентом
		$message = "WebSocket: Connection closed {$connectionId}.";
		echo "{$message}\n";
		unset($this->userIds[$connectionId]);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \morozovsk\websocket\Daemon::onMessage()
	 */
	protected function onMessage($connectionId, $data, $type)
	{
		// вызывается при получении сообщения от клиента
		$message = "WebSocket: Client message from connection #{$connectionId}. Type: {$type}, data: " . var_export($data, true);
		echo "{$message}\n";
		
		if ($type == 'ping') {
			$this->sendToClient($connectionId, '', 'pong');
		}
		if ($type == 'close') {
			$this->close($connectionId);
		}
	}

	/**
	 * {@inheritDoc}
	 * @see \morozovsk\websocket\Daemon::onServiceMessage()
	 */
	protected function onServiceMessage($connectionId, $data)
	{
		$data = Json::decode($data);
		
		$recieverUserId = isset($data['userId']) ? $data['userId'] : null;
		if (empty($recieverUserId)) {
			return;
		}
		
		$message = $data;
		
		foreach ($this->userIds as $clientId => $userId) {
			if ($recieverUserId == $userId) {
				try {
					$this->sendToClient($clientId, Json::encode($message));
				} catch (\yii\db\Exception $e) {
					$this->reopenDbConnection();
					$this->onOpen($connectionId, $info);
				} catch (\Exception $e) {
					$error = @var_export($e, true);
					echo "WebSocket Send Message Exception:\n";
					echo $error . "\n";
					\Yii::error($error, 'websocket');
					$this->close($clientId);
				}
			}
		}
	}

	/**
	 * {@inheritDoc}
	 * @see \morozovsk\websocket\Daemon::_decode()
	 */
	protected function _decode($connectionId)
	{
		if (!isset($this->_read[$connectionId])) {
			return false;
		}
		return parent::_decode($connectionId);
	}
}