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
            'policy' => $this->string(16),
            'snils' => $this->string(14),
            'address' => $this->string(),
            'anamnesis' => $this->text(),
            'chatId' => $this->integer(),
            'fileId' => $this->integer(),
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

        $this->addForeignKey(
            'fk-incident-file-1',
            'incident',
            'fileId',
            'file',
            'fileId',
            'SET NULL'
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

        $this->dropForeignKey(
            'fk-incident-file-1',
            'incident'
        );

        $this->dropTable('incident');
    }
}
