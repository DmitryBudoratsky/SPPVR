<?php

namespace console\migrations\message;

use yii\db\Migration;

class M250523082918CreateTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('message', [
            'messageId' => $this->primaryKey(),
            'chatId' => $this->integer()->notNull(),
            'userId' => $this->integer(),
            'type' => $this->tinyInteger()->notNull(),
            'text' => $this->text(),
            'createdAt' => $this->bigInteger(),
            'updatedAt' => $this->bigInteger()
        ]);

        $this->addForeignKey(
            'fk-message-chat-1',
            'message',
            'chatId',
            'chat',
            'chatId',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-message-user-1',
            'message',
            'userId',
            'user',
            'userId',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-message-chat-1',
            'message'
        );

        $this->dropForeignKey(
            'fk-message-user-1',
            'message'
        );
        
        $this->dropTable('message');
    }
}
