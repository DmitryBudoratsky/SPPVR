<?php

namespace console\migrations\incident;

use yii\db\Migration;

class M250615200140AddColumnVerdictAuthorId extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('incident', 'verdictAuthorId', $this->integer());

        $this->addForeignKey(
            'fk-incident-user-1',
            'incident',
            'verdictAuthorId',
            'user',
            'userId',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-incident-user-1',
            'incident'
        );

        $this->dropColumn('incident', 'verdictAuthorId');
    }
}
