<?php

namespace console\migrations\incident;

use yii\db\Migration;

class M250515200755AddColumnVerdictAt extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('incident', 'verdictAt', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('incident', 'verdictAt');
    }
}
