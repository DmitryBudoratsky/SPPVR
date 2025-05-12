<?php

namespace console\migrations\incident;

use yii\db\Migration;

class M250512201403AddColumnVerdict extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('incident', 'verdict', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('incident', 'verdict');
    }
}
