<?php
namespace frontend\modules\api\controllers\open;

use OpenApi\Annotations as OA;
use yii\filters\VerbFilter;
use yii\web\Response;
use frontend\modules\api\models\appSettings\AppSettingsForm;
use frontend\modules\api\controllers\BaseApiController;

class AppSettingsController extends BaseApiController
{
	public function behaviors()
	{
		$behaviors = parent::behaviors();
        $behaviors['verbs'] = [
			'class' => VerbFilter::className(),
			'actions' => [
				'app-settings' => ['get']
			]
		];
        return $behaviors;
	}

    /**
     * @OA\Get(path="/open/app-settings/app-settings",
     *     tags={"Common"},
     *     summary="Настройки приложения",
     *     @OA\Response(response = 200, description = "Ответ",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="appConfig", ref="#/components/schemas/AppSettings"),
     *             ),
     *         ),
     *     ),
     * )
     */
	public function actionAppSettings()
	{
        $model = new AppSettingsForm();
		return $model->getAppSettings();
	}
}