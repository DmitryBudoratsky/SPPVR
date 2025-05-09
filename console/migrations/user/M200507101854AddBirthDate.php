<?php

namespace console\migrations\user;

use yii\db\Migration;

/**
 * Class M200507101854AddBirthDate
 */
class M200507101854AddBirthDate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'dateOfBirth', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'dateOfBirth');
    }
}
