<?php


namespace backend\controllers;

/**
 * Description of PrivateController
 *
 */
class PrivateController extends \yii\web\Controller
{
    // Отображает страницу на всю ширину экрана
    const LAYOUT_FULLSIZE = 'fullsize';

	public function behaviors()
	{
		return [
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'rules' => [
					// deny all not authenticated users
					[
						'allow' => false,
						'roles' => ['?'],
					],
					// allow authenticated users
					[
						'allow' => true,
						'roles' => ['@'],
					],
					// everything else is denied
				],
			],
		];
	}

    public function stdout($string)
    {
        \Yii::debug($string);
    }
}
