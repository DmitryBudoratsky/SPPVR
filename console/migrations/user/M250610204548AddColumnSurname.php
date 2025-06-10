<?php

namespace console\migrations\user;

use yii\db\Migration;

class M250610204548AddColumnSurname extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'surname', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'surname');
    }
}
