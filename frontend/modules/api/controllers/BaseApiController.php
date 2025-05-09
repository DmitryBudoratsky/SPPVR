<?php

namespace frontend\modules\api\controllers;

use Yii;
use yii\base\Controller;
use yii\web\Response;
use common\models\db\User;
use yii\filters\ContentNegotiator;

/**
 * Базовый класс для всех API Controller'ов
 * - обеспечивает обработку accessToken (при наличии)
 * - и сохранение авторизованного пользователя по accessToken, которого позже можно получить через User::getUser()
 */
class BaseApiController extends Controller
{
	/**
	 * {@inheritDoc}
	 * @see \yii\rest\Controller::behaviors()
	 */
	public function behaviors()
	{
		$behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
			'class' => ContentNegotiator::className(),
			'formats' => [
				'application/json' => Response::FORMAT_JSON,
			],
		];
        return $behaviors;
	}

	/**
	 * {@inheritDoc}
	 * @see \yii\web\Controller::beforeAction()
	 */
	public function beforeAction($action)
	{
		if (!parent::beforeAction($action)) {
			return false;
		}
        $user = $this->getUser();
		if (!empty($user)) {
			\Yii::$app->user->login($user);
		}
		return true;
	}

    /**
     * Get user by token from query string
     * @return User
     */
	protected function getUser()
	{
		$accessToken = $this->getAccessToken();
		/**
		 * @var User $user
		 */
		$user = User::findIdentityByAccessToken($accessToken);
		if (!empty($user) && $user->status == User::STATUS_ACTIVE) {
		    $user->lastActiveAt = time();
		    $user->save();
		}

		return $user;
	}
	
	/**
     * Get access token
	 * @return array|mixed
	 */
	private function getAccessToken()
	{
		$accessToken = Yii::$app->request->get("accessToken");
		if (empty($accessToken)) {
			$accessToken = Yii::$app->request->post("accessToken");
		}
		return $accessToken;
	}

    public function stdout($string)
    {
        \Yii::debug($string);
    }
}