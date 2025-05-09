<?php

namespace console\migrations\file;

use yii\db\Migration;

/**
 * Class M190205130335VideoFile
 */
class M190205130335VideoFile extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('videoFile', [
            'videoFileId' => $this->primaryKey(),
            'fileId' => $this->integer()->notNull(),
            'previewImageFileId' => $this->integer()->notNull(),
            'width' => $this->integer(),
            'height' => $this->integer(),
            'createdAt' => $this->bigInteger(),
            'updatedAt' => $this->bigInteger()
        ]);

        $this->addForeignKey(
            'fk-videoFile-file-1',
            'videoFile',
            'fileId',
            'file',
            'fileId',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-videoFile-file-2',
            'videoFile',
            'previewImageFileId',
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
        $this->dropForeignKey('fk-videoFile-file-1','videoFile');
        $this->dropForeignKey('fk-videoFile-file-2','videoFile');
        $this->dropTable('videoFile');
    }
}
