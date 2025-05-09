<?php

namespace console\migrations\file;

use yii\db\Migration;

/**
 * Handles the creation of table `imageFile`.
 */
class m170210_134927_create_imageFile_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('imageFile', [
            'imageFileId' => $this->primaryKey(),
            'fileId' => $this->integer()->notNull(),
            'previewUrl' => $this->string(),
            'width' => $this->integer(),
            'height' => $this->integer(),
            'previewWidth' => $this->integer(),
            'previewHeight' => $this->integer(),
        	'createdAt' => $this->bigInteger(),
        	'updatedAt' => $this->bigInteger()
        ]);     
        
        $this->addForeignKey(
        	'fk-imageFile-file-1',
        	'imageFile',
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
    	$this->dropForeignKey(
    		'fk-imageFile-file-1',
    		'imageFile'
    	);
    	
        $this->dropTable('imageFile');
    }
}
