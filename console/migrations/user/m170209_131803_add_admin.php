<?php

namespace console\migrations\user;

use yii\db\Migration;
use common\models\db\User;
use common\models\db\Settings;

class m170209_131803_add_admin extends Migration
{
    public function safeUp()
    {
    	$email = \Yii::$app->params['adminDefaultEmail'];
    	$this->insert('user', array(
    		'email' =>	$email,
    		'passwordHash' => \Yii::$app->security->generatePasswordHash(\Yii::$app->params['adminDefaultPassword']),
    		'name' => stristr($email, '@', true),
    		'role' => User::ROLE_ADMIN,
    		'createdAt' => time(),
    		'updatedAt' => time()
    	));
    }
}
