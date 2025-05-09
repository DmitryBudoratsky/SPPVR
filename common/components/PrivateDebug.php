<?php

namespace common\components;

use common\models\db\User;

class PrivateDebug extends \yii\debug\Module
{
    private $_basePath;
    /**
     * Checks if current user is allowed to access the module
     * @param \yii\base\Action|null $action the action to be executed. May be `null` when called from
     * a non action context
     * @return bool if access is granted
     */
    protected function checkAccess($action = null)
    {
        $user = \Yii::$app->getUser();

        if (!($user->identity && User::getUser()->role == User::ROLE_ADMIN)) {
            return false;
        }
        return parent::checkAccess();
    }

    /**
     * @return string root directory of the module.
     * @throws \ReflectionException
     */
    public function getBasePath()
    {
        if ($this->_basePath === null) {
            $class = new \ReflectionClass(new \yii\debug\Module('debug'));
            $this->_basePath = dirname($class->getFileName());
        }

        return $this->_basePath;
    }
}
