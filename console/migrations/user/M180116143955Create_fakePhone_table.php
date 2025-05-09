<?php

namespace console\migrations\user;

use yii\db\Migration;

/**
 * Class M180116143955Create_fakePhone_table
 */
class M180116143955Create_fakePhone_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('fakePhone', [
            'fakePhoneId' => $this->primaryKey(),
            'phone' => $this->string()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('fakePhone');
    }
}
