<?php

namespace console\migrations\incident;

use yii\db\Migration;

class M250510191400CreateTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('incident', [
            'incidentId' => $this->primaryKey(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'patientName' => $this->string(),
            'birthDate' => $this->bigInteger(),
            'sex' => $this->tinyInteger()->notNull()->defaultValue(0),
            'policy' => $this->string(16),
            'snils' => $this->string(14),
            'address' => $this->string(),
            'anamnesis' => $this->text(),
            'chatId' => $this->integer(),
            'createdAt' => $this->bigInteger(),
            'updatedAt' => $this->bigInteger()
        ]);

        $this->addForeignKey(
            'fk-incident-chat-1',
            'incident',
            'chatId',
            'chat',
            'chatId',
            'SET NULL'
        );

        $this->createIndex(
            'incident-chatId-unique',
            'incident',
            'chatId',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-incident-chat-1',
            'incident'
        );

        $this->dropIndex(
            'incident-chatId-unique',
            'incident'
        );

        $this->dropTable('incident');
    }
}
