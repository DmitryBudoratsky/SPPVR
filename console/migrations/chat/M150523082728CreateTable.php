<?php

namespace console\migrations\chat;

use yii\db\Migration;

class M150523082728CreateTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('chat', [
            'chatId' => $this->primaryKey(),
            'createdAt' => $this->bigInteger(),
            'updatedAt' => $this->bigInteger()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('chat');
    }
}
