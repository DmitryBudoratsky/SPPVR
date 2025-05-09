<?php

namespace console\migrations\chat;

use yii\db\Migration;

/**
 * Handles the creation of table `chatMember`.
 */
class m170214_102146_create_chatMember_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('chatMember', [
            'chatMemberId' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'chatId' => $this->integer()->notNull(),
        	'createdAt' => $this->bigInteger(),
        	'updatedAt' => $this->bigInteger()
        ]);
        
        $this->addForeignKey(
        	'fk-chatMember-user-1',
        	'chatMember',
        	'userId',
        	'user',
        	'userId',
        	'CASCADE'
        );
        
        $this->addForeignKey(
        	'fk-chatMember-chat-1',
        	'chatMember',
        	'chatId',
        	'chat',
        	'chatId',
        	'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
    	$this->dropForeignKey(
    		'fk-chatMember-user-1',
    		'chatMember'
    	);
    	
    	$this->dropForeignKey(
    		'fk-chatMember-chat-1',
    		'chatMember'
    	);
    	
        $this->dropTable('chatMember');
    }
}
