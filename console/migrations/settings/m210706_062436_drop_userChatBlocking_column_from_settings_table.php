<?php

namespace console\migrations\settings;

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%settings}}`.
 */
class m210706_062436_drop_userChatBlocking_column_from_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%settings}}', 'userChatBlocking');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%settings}}', 'userChatBlocking', $this->json());
    }
}
