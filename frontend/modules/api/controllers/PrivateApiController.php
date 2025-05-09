<?php

namespace frontend\modules\api\controllers;

use Yii;
use common\models\db\User;

/**
 * Базовый класс для большей части API Controller'ов
 * - обеспечивает контроль доступа к определенным API только авторизованным пользователям
 */
class PrivateApiController extends BaseApiController
{

    /**
     * @return array
     */
    protected function openActions() {
        return [];
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
		if (in_array($action->id, $this->openActions())) {
		    return true;
        }
		/**
		 * @var User $user
		 */
		$user = User::getUser();
		if (empty($user) || $user->status != User::STATUS_ACTIVE) {
			return $this->getResponseFormatForInvalidAccessToken();
		}
		return true;
	}

	private function getResponseFormatForInvalidAccessToken()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		echo json_encode([
			"meta" => [
				'success' => false,
				'error' => 'Для вызова API необходим активный accessToken',
				'invalidAccessToken' => true,
			],
			"data" => [],
		]);
		exit();
	}
}