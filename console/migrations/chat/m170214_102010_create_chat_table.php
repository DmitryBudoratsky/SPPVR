<?php

namespace console\migrations\chat;

use yii\db\Migration;

/**
 * Handles the creation of table `chat`.
 */
class m170214_102010_create_chat_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('chat', [
            'chatId' => $this->primaryKey(),
            'isHidden' => $this->integer(),
        	'createdAt' => $this->bigInteger(),
        	'updatedAt' => $this->bigInteger()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {   	
        $this->dropTable('chat');
    }
}
