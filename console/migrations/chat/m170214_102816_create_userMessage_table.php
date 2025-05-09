<?php

namespace console\migrations\chat;

use yii\db\Migration;
use common\models\db\UserMessage;

/**
 * Handles the creation of table `userMessage`.
 */
class m170214_102816_create_userMessage_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('userMessage', [
            'userMessageId' => $this->primaryKey(),
        	'messageId' => $this->integer()->notNull(),
            'userId' => $this->integer()->notNull(),
			'status' => $this->integer()->defaultValue(0),
        	'createdAt' => $this->bigInteger(),
        	'updatedAt' => $this->bigInteger()
        ]);
        
        $this->addForeignKey(
        	'fk-userMessage-message-1',
        	'userMessage',
        	'messageId',
        	'message',
        	'messageId',
        	'CASCADE'
        );
        
        $this->addForeignKey(
        	'fk-userMessage-user-1',
        	'userMessage',
        	'userId',
        	'user',
        	'userId',
        	'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
    	$this->dropForeignKey(
    		'fk-userMessage-message-1',
    		'userMessage'
    	);
    	
    	$this->dropForeignKey(
    		'fk-userMessage-user-1',
    		'userMessage'
    	);
    	
        $this->dropTable('userMessage');
    }
}
