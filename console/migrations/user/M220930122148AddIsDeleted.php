<?php

namespace console\migrations\user;

use yii\db\Migration;

/**
 * Class M220930122148AddIsDeleted
 */
class M220930122148AddIsDeleted extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'isDeleted', $this->tinyInteger(1)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'isDeleted');
    }
}
