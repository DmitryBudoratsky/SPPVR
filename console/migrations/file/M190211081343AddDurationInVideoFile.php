<?php

namespace console\migrations\file;

use yii\db\Migration;

/**
 * Class M190211081343AddDurationInVideoFile
 */
class M190211081343AddDurationInVideoFile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('videoFile', 'duration', $this->decimal(10,2)->defaultValue(0)->after('height'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('videoFile', 'duration');
    }
}
