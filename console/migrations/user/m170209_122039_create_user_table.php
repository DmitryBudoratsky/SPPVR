<?php

namespace console\migrations\user;

use yii\db\Migration;
use common\models\db\User;

/**
 * Handles the creation of table `user`.
 */
class m170209_122039_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'userId' => $this->primaryKey(),
        	'name' => $this->string(),
        	'lastname' => $this->string(),
        	'passwordHash' => $this->string(),
        	'login' => $this->string(),
            'status' => $this->smallInteger()->notNull()->defaultValue(User::STATUS_ACTIVE),
        	'role' => $this->smallInteger()->notNull()->defaultValue(User::ROLE_DEFAULT_USER),
            'email' => $this->string()->null()->unique(),
        	'isEmailConfirmed' => $this->integer()->null()->defaultValue(User::IS_NOT_EMAIL_CONFIRMED),
        	'phone' => $this->string()->unique(),
        	'isAlreadyRegistered' => $this->integer()->defaultValue(User::IS_NOW_REGISTERED),
        	'avatarFileId' => $this->integer(),
        	'vkUserId' => $this->string(),
        	'facebookUserId' => $this->string(),
        	'twitterUserId' => $this->string(),
        	'authKey' => $this->string(32),
        	'createdAt' => $this->bigInteger(),
        	'updatedAt' => $this->bigInteger()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}