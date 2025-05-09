<?php

namespace console\migrations\user;

use yii\db\Migration;
use common\models\db\User;
use common\models\db\Settings;

class M170907080851Add_testAdmin extends Migration
{
    public function safeUp()
    {
    	$email = \Yii::$app->params['testAdminDefaultEmail'];
    	$login = stristr($email, '@', true);
    	$this->insert('user', array(
    		'email' =>	$email,
    		'passwordHash' => \Yii::$app->security->generatePasswordHash(\Yii::$app->params['adminDefaultPassword']),
    		'login' => $login,
    		'name' => $login,
    		'role' => User::ROLE_ADMIN,
    		'createdAt' => time(),
    		'updatedAt' => time()
    	));
    }
}
