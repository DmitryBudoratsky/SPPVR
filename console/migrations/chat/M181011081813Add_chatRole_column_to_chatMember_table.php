<?php

namespace console\migrations\chat;

use yii\db\Migration;

/**
 * Class M181011081813Add_chatRole_column_to_chatMember_table
 */
class M181011081813Add_chatRole_column_to_chatMember_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('chatMember', 'chatRole', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('chatMember', 'chatRole');
    }

}
