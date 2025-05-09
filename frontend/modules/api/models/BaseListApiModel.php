<?php


namespace frontend\modules\api\models;


use yii\base\Model;
use common\models\db\Settings;

/**
 * Базовая модель для всех API, возвращающих списки данных
 * - обеспечивает обработку стандартных полей limit и offset для пейджинации по списку данных
 */
class BaseListApiModel extends BaseApiModel
{
    public $limit;
    public $offset;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['limit', 'offset'], 'filter', 'filter' => 'trim'],
            [['limit', 'offset'], 'integer'],
            ['offset', 'default', 'value' => 0],
            ['limit', 'default', 'value' => \Yii::$app->params['limitRecordsOnPage']],
        ]);
    }
}