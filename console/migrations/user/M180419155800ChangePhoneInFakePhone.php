<?php

namespace console\migrations\user;

use yii\db\Migration;

/**
 * Class M180419155800ChangePhoneInFakePhone
 */
class M180419155800ChangePhoneInFakePhone extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->alterColumn('fakePhone', 'phone', $this->string()->notNull()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->alterColumn('fakePhone', 'phone', $this->string()->notNull());
    }
}
