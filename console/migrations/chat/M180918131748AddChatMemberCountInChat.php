<?php

namespace console\migrations\chat;

use yii\db\Migration;

/**
 * Class M180918131748AddChatMemberCountInChat
 */
class M180918131748AddChatMemberCountInChat extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('chat', 'chatMemberCount', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('chat', 'chatMemberCount');
    }
}
