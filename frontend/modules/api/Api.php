<?php

namespace frontend\modules\api;
use yii;
use yii\base\Module;
use yii\web\Response;
use OpenApi\Annotations as OA;

/**
 * Класс API.
 * Обеспечивает общий формат возврата данных от API
 * - JSON
 * - Формат возврата данных
 * - Формат возврата ошибок
 */
/**
 * @OA\OpenApi(
 *     openapi="3.0.0",
 *     info=@OA\Info(
 *         title="Base Backend API",
 *         description="Документация для API базового сервера.",
 *         version="0.0.1"
 *     ),
 *     security={
 *          {"accessToken" = {}}
 *     }
 * )
 * @OA\Server(url="http://82.146.53.185:8104/api")
 * @OA\SecurityScheme(securityScheme="accessToken", type="apiKey", name="accessToken", in="query")
 * @OA\Tag(name="Common", description="Общие методы")
 */
class Api extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\api\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::$app->user->enableSession = false;
        $this->registerResponseComponent();
    }
      
    private function registerResponseComponent()
    {
        \Yii::$app->set('response', [
            'class' => 'yii\web\Response',
            'format' => Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
        
                if (($response->data !== null) && is_array($response->data)) {
                	// ответ с ошибками
                	if (!$response->isSuccessful) {
                		$response->data = ["meta" => ['success' => $response->isSuccessful, 
                			'error' => isset($response->data["message"]) ? $response->data["message"] : '',
                		], "data" => $response->data];
                	} else {	
                		// положительный ответ
	                    $response->data = ["meta" => ['success' => $response->isSuccessful, 'error' => ''], "data" => $response->data]; 
                	}
                    $response->format = yii\web\Response::FORMAT_JSON;
                    if (YII_DEBUG) {
                        \Yii::trace('Response . ' . var_export($response->data, true));
                    }
                } else if (is_string($response->data)) {
                    $response->format = yii\web\Response::FORMAT_RAW;
                }
                $response->statusCode = 200;
            },
        ]);
    }
}
