<?php

namespace console\migrations\chat;

use yii\db\Migration;

/**
 * Class M181009114451Add_isPublic_column_to_chat_table
 */
class M181009114451Add_isPublic_column_to_chat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('chat', 'isPublic', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('chat', 'isPublic');
    }

}
