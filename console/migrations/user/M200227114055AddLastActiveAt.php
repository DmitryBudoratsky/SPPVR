<?php

namespace console\migrations\user;

use yii\db\Migration;

/**
 * Class M200227114055AddLastActiveAt
 */
class M200227114055AddLastActiveAt extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'lastActiveAt', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'lastActiveAt');
    }
}
