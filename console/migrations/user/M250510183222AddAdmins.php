<?php

namespace console\migrations\user;

use common\models\db\User;
use yii\db\Migration;

class M250510183222AddAdmins extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach (\Yii::$app->params['adminEmails'] as $email) {
            $this->insert('user', array(
                'email' => $email,
                'passwordHash' => \Yii::$app->security->generatePasswordHash(\Yii::$app->params['adminDefaultPass']),
                'name' => stristr($email, '@', true),
                'role' => User::ROLE_ADMIN,
                'createdAt' => time(),
                'updatedAt' => time()
            ));
        }
    }

    public function safeDown()
    {
        $this->delete('user', [
            'email' => \Yii::$app->params['adminEmails']
        ]);
    }
}
