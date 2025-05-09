<?php

namespace console\migrations\file;

use yii\db\Migration;

/**
 * Class M181015122546LinkedFile
 */
class M181015122546LinkedFile extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('linkedFile', [
            'linkedFileId' => $this->primaryKey(),
            'fileId' => $this->integer()->notNull(),
        	'itemType' => $this->string()->notNull(),
        	'itemId' => $this->integer()->notNull(),
        	'createdAt' => $this->bigInteger(),
        	'updatedAt' => $this->bigInteger()
        ]);
        
        $this->addForeignKey(
        	'fk-linkedFile-file-1',
        	'linkedFile',
        	'fileId',
        	'file',
        	'fileId',
        	'CASCADE',
        	'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
    	$this->dropForeignKey('fk-linkedFile-file-1', 'linkedFile');
        $this->dropTable('linkedFile');
    }
}
