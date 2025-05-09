<?php

namespace console\migrations\file;

use yii\db\Migration;

/**
 * Handles the creation of table `file`.
 */
class m170209_130931_create_file_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('file', [
            'fileId' => $this->primaryKey(),
            'url' => $this->string(),
            'type' => $this->string(),
            'mimeType' => $this->string(),
        	'originalName' => $this->string(),
        	'createdAt' => $this->bigInteger(),
        	'updatedAt' => $this->bigInteger()
        ]);
        
        $this->addForeignKey(
        	'fk-user-file-1',
        	'user',
        	'avatarFileId',
        	'file',
        	'fileId',
        	'SET NULL'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
    	$this->dropForeignKey(
    		'fk-user-file-1',
    		'user'
    	);
        $this->dropTable('file');
    }
}
