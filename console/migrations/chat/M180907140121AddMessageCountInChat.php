<?php

namespace console\migrations\chat;

use yii\db\Migration;

/**
 * Class M180907140121AddMessageCountInChat
 */
class M180907140121AddMessageCountInChat extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('chat', 'messageCount', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('chat', 'messageCount');
    }
}
