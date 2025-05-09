<?php

namespace console\migrations\user;

use yii\db\Migration;

/**
 * Handles the creation of table `sendedSms`.
 */
class m170213_114454_create_sendedSms_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('sendedSms', [
            'sendedSmsId' => $this->primaryKey(),
            'phone' => $this->string()->notNull(),
            'message' => $this->string()->notNull(),
            'smsServerResponse' => $this->text(),
        	'createdAt' => $this->bigInteger(),
        	'updatedAt' => $this->bigInteger()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('sendedSms');
    }
}
