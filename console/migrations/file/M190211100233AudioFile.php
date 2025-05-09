<?php

namespace console\migrations\file;

use yii\db\Migration;

/**
 * Class M190211100233AudioFile
 */
class M190211100233AudioFile extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('audioFile', [
            'audioFileId' => $this->primaryKey(),
            'fileId' => $this->integer()->notNull(),
            'duration' => $this->decimal(10,2)->defaultValue(0),
            'createdAt' => $this->bigInteger(),
            'updatedAt' => $this->bigInteger()
        ]);

        $this->addForeignKey(
            'fk-audioFile-file-1',
            'audioFile',
            'fileId',
            'file',
            'fileId',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-audioFile-file-1','audioFile');
        $this->dropTable('audioFile');
    }
}
