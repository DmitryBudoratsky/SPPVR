<?php

namespace frontend\modules\api\models;

use yii\base\Model;
use yii;
use common\models\db\User;

/**
 * Базовая модель для всех API
 * - обеспечивает быстрый доступ к данным авторизованного пользователя (при наличии)
 */
class BaseApiModel extends Model
{

    /**
     * @var User $_user;
     */
    protected $_user;

    public function init()
    {
        parent::init();
        $this->_user = User::getUser();
    }

}