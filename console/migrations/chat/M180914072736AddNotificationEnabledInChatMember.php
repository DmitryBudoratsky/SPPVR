<?php

namespace console\migrations\chat;

use yii\db\Migration;

/**
 * Class M180914072736AddNotificationEnabledInChatMember
 */
class M180914072736AddNotificationEnabledInChatMember extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('chatMember', 'notificationEnabled', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('chatMember', 'notificationEnabled');
    }
}
