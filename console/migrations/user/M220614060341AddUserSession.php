<?php

namespace console\migrations\user;

use yii\db\Migration;

/**
 * Class M220614060341AddUserSession
 */
class M220614060341AddUserSession extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('userSession', [
            'id' => $this->char(40)->notNull(),
            'expire' => $this->integer(),
            'data' => $this->binary(),
            'user_id' => $this->integer()
        ]);

        $this->addPrimaryKey('session_pk', 'userSession', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('session_pk', 'userSession', 'id');

        $this->dropTable('userSession');
    }
}
