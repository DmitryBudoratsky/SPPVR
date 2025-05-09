<?php

namespace console\migrations\user;

use yii\db\Migration;
use common\models\db\ConfirmEmailRequest;

/**
 * Handles the creation of table `confirmEmailRequest`.
 */
class m170223_145713_create_confirmEmailRequest_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('confirmEmailRequest', [
			'confirmEmailRequestId' => $this->primaryKey(),
			'userId' => $this->integer()->notNull(),
			'confirmEmailToken' => $this->string()->unique()->notNull(),
			'isUsed' => $this->integer()->defaultValue(0),
		]);
	
		$this->addForeignKey(
			"fk-confirmEmailRequest-user-1",
			"confirmEmailRequest",
			"userId",
			"user",
			"userId",
			'CASCADE'
		);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropForeignKey(
			'fk-confirmEmailRequest-user-1',
			'confirmEmailRequest'
		);
		 
		$this->dropTable('confirmEmailRequest');
	}
}
