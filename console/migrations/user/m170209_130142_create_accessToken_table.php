<?php

namespace console\migrations\user;

use yii\db\Migration;

/**
 * Handles the creation of table `accessToken`.
 */
class m170209_130142_create_accessToken_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('accessToken', [
            'accessTokenId' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'token' => $this->string()->notNull(),
        	'createdAt' => $this->bigInteger(),
        	'updatedAt' => $this->bigInteger()
        ]);
        
        $this->addForeignKey(
        	'fk-accessToken-user-1',
        	'accessToken',
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
    		'fk-accessToken-user-1',
    		'accessToken'
    	);
    	
        $this->dropTable('accessToken');
    }
}
