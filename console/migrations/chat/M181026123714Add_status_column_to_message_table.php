<?php

namespace console\migrations\chat;

use yii\db\Migration;

/**
 * Class M181026123714Add_status_column_to_message_table
 */
class M181026123714Add_status_column_to_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('message', 'status', $this->smallInteger()->defaultValue(1)->notNull());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('message', 'status');
    }

}
