<?php

namespace console\migrations\user;

use yii\db\Migration;
use common\models\db\PasswordResetRequest;

/**
 * Handles the creation of table `passwordResetRequest`.
 */
class m170209_124938_create_passwordResetRequest_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('passwordResetRequest', [
            'passwordResetRequestId' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'passwordResetToken' => $this->string()->unique()->notNull(),
            'isUsed' => $this->integer()->defaultValue(0),
            'expirationDate' => $this->bigInteger(),
        	'createdAt' => $this->bigInteger(),
        	'updatedAt' => $this->bigInteger()
        ]);
        
        $this->addForeignKey(
        	"fk-passwordResetRequest-user-1",
        	"passwordResetRequest", 
        	"userId",
        	"user", 
        	"userId",
        	"CASCADE", 
        	"CASCADE"
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
    	$this->dropForeignKey(
    		'fk-passwordResetRequest-user-1',
    		'passwordResetRequest'
    	);
    	
        $this->dropTable('passwordResetRequest');
    }
}