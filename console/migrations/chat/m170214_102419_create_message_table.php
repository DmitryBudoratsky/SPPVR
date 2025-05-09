<?php

namespace console\migrations\chat;

use yii\db\Migration;
use common\models\db\Message;

/**
 * Handles the creation of table `message`.
 */
class m170214_102419_create_message_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('message', [
            'messageId' => $this->primaryKey(),
        	'chatId' => $this->integer()->notNull(),
            'senderUserId' => $this->integer(),
            'text' => $this->text(),
        	'isAutoMessage' => $this->integer()->defaultValue(0),
        	'fileId' => $this->integer(),
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
        	'senderUserId',
        	'user',
        	'userId',
        	'CASCADE'
        );
        
        $this->addForeignKey(
        	'fk-message-file-1',
        	'message',
        	'fileId',
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
    		'fk-message-chat-1',
    		'message'
    	);
    	
    	$this->dropForeignKey(
    		'fk-message-user-1',
    		'message'
    	);
    	
    	$this->dropForeignKey(
    		'fk-message-file-1',
    		'message'
    	);
    	
        $this->dropTable('message');
    }
}
