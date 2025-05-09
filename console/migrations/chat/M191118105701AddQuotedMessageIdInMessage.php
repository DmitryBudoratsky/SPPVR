<?php

namespace console\migrations\chat;

use yii\db\Migration;

/**
 * Class M191118105701AddQuotedMessageIdInMessage
 */
class M191118105701AddQuotedMessageIdInMessage extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('message', 'quotedMessageId', $this->integer());

        $this->addForeignKey(
            'fk-message-message-1',
            'message',
            'quotedMessageId',
            'message',
            'messageId',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-message-message-1', 'message');
        $this->dropColumn('message', 'quotedMessageId');
    }
}
