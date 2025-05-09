<?php

namespace frontend\modules\api\models\appSettings;

use OpenApi\Annotations as OA;
use yii\base\Model;
use common\models\db\AboutUs;
use common\models\db\Page;
use yii\db\ActiveQuery;

/**
 * @OA\Schema(schema="AppSettings", description="Настройки приложения", properties={
 *     @OA\Property(property="supportUrl", type="string", description="Адрес технической поддержки"),
 *     @OA\Property(property="pages", type="object", description="Словарь со ссылками на информационные страницы"),
 *     @OA\Property(property="appSupportEmail", type="string", description="googlePlayUrl"),
 *     @OA\Property(property="appStoreUrl", type="string", description="Ссылка на приложение в AppStore"),
 *     @OA\Property(property="googlePlayUrl", type="string", description="Ссылка на приложение в GooglePlay"),
 *     @OA\Property(property="limitRecordsOnPage", type="integer", description="Лимит записей при порционной загрузке"),
 *     @OA\Property(property="textShareForPromoCode", type="string", description="Текст для приглашения по промо коду"),
 *     @OA\Property(property="codeLength", type="string", description="Длина смс-кода подтверждения"),
 *     @OA\Property(property="contacts", ref="#/components/schemas/AboutUs", description="О нас"),
 * })
 */
class AppSettingsForm extends Model
{
    /**
     * @return array
     */
    public function getAppSettings()
    {
    	$appSettingsArr = [];
    	
    	$appSettingsArr["supportUrl"] = \Yii::$app->params['supportUrl'];

        if (\Yii::$app->db->getTableSchema('{{%aboutUs}}', true) !== null) {
            /** @var AboutUs $aboutUs */
            $aboutUs = AboutUs::find()->one();
            if (!empty($aboutUs)) {
                $appSettingsArr["contacts"] = $aboutUs->serializeToArrayShort();
            }
        }

        if (\Yii::$app->db->getTableSchema('{{%page}}', true) !== null) {
            $pagesQuery = Page::find();
            $pagesInfoObj = [];
            foreach ($pagesQuery->each() as /** @var Page $page */ $page) {
                $pagesInfoObj[$page->key] = \Yii::$app->urlManager->createAbsoluteUrl(['/site/page', 'key' => $page->key]);
            }
            $appSettingsArr["pages"] = $pagesInfoObj;
        }

        // Служба поддержки для мобильных приложений
        $appSettingsArr['appSupportEmail']  = \Yii::$app->params['appSupportEmail'];
        $appSettingsArr['appStoreUrl']      = \Yii::$app->params['appStoreUrl'];
        $appSettingsArr['googlePlayUrl']    = \Yii::$app->params['googlePlayUrl'];


        $appSettingsArr['limitRecordsOnPage'] = \Yii::$app->params['limitRecordsOnPage'];

        $appSettingsArr['codeLength'] = \Yii::$app->params['codeLength'];



    	return ["appConfig" => $appSettingsArr];
    }
}