<?php

namespace common\components\ratchet;

use common\models\db\AccessToken;
use yii\helpers\Json;
use common\components\Yii;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsServer;

class RatchetHandler implements MessageComponentInterface
{
    protected $clients;
    public function __construct()
    {
        $this->clients = new \SplObjectStorage; // Для хранения технической информации об присоединившихся клиентах используется технология SplObjectStorage, встроенная в PHP
    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo "Trying to open connection #{$conn->resourceId}, " . var_export($this->clients->getInfo(), true) . "\n";
        echo "New connection! ({$conn->resourceId})\n";
        $accessTokenString = str_replace("accessToken=", "", $conn->httpRequest->getUri()->getQuery());
        try {
            echo "Connection #{$conn->resourceId}. Access token: " . $accessTokenString . "\n";
            /**
             *
             * @var AccessToken $accessToken
             */
            $accessToken = AccessToken::find()->andWhere(['token' => $accessTokenString])->one();
            if (empty($accessToken)) {
                echo "Connection #{$conn->resourceId}. Empty access token\n";
                $this->onClose($conn);
                return;
            }
            $conn->resourceId = $accessToken->userId;
            $this->clients->attach($conn);

//            $this->clients[$conn->resourceId] = $accessToken->userId;

            $connectedMessage = "WebSocket: User #{$accessToken->userId} connected to {$conn->resourceId}.";
            echo "{$connectedMessage}\n";
            \Yii::info($connectedMessage, 'websocket');
        } catch (\yii\db\Exception $e) {
            echo "DB Exception: " . $e->getMessage() . "\n";
            $this->reopenDbConnection();
            $this->onOpen($conn);
        } catch (\Exception $e) {
            $error = @var_export($e, true);
            echo "WebSocket Exception:\n";
            echo $error . "\n";
            \Yii::error($error, 'websocket');
            $this->onClose($conn);
        }
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
//        {"data": "Hello 203!", "userId": 203, "socketType": 3}
        $data = json_decode($msg, true); //для приема сообщений в формате json
        if (is_null($data))
        {
            echo "invalid data\n";
            return $from->close();
        }

        foreach ($this->clients as $client) {
            echo var_export($client->resourceId) . "\n";
            echo var_export($data) . "\n";
            if (!empty($data["userId"]) && $data["userId"] == $client->resourceId) {
                $client->send($msg);
                echo var_export($data) . "\n";
            }
        }

        echo $from->resourceId."\n";//id, присвоенное подключившемуся клиенту
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * Reopens DB connection.
     */
    private function reopenDbConnection()
    {
        \Yii::$app->getDb()->close();
        gc_collect_cycles();
    }
}
