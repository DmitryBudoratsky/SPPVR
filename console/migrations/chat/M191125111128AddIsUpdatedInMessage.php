<?php

namespace console\migrations\chat;

use yii\db\Migration;

/**
 * Class M191125111128AddIsUpdatedInMessage
 */
class M191125111128AddIsUpdatedInMessage extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('message', 'isUpdated', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('message', 'isUpdated');
    }
}
