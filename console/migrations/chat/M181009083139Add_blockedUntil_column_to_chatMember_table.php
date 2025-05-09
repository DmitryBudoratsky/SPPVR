<?php

namespace console\migrations\chat;

use yii\db\Migration;

/**
 * Class M181009083139Add_blockedUntil_column_to_chatMember_table
 */
class M181009083139Add_blockedUntil_column_to_chatMember_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('chatMember', 'blockedUntil', $this->bigInteger()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('chatMember', 'blockedUntil');
    }

}
