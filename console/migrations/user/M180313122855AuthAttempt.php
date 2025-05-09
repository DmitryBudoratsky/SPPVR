<?php

namespace console\migrations\user;

use yii\db\Migration;

/**
 * Class M180313122855AuthAttemp
 */
class M180313122855AuthAttempt extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('authAttempt', [
			'authAttemptId' => $this->primaryKey(),
			'userId' => $this->integer()->notNull(),
			'type' => $this->integer(),
			'ipAddress' => $this->string()->notNull(),
			'isSuccessful' => $this->integer()->defaultValue(0),
			'createdAt' => $this->bigInteger(),
			'updatedAt' => $this->bigInteger()
		]);
	
		$this->addForeignKey(
			"fk-authAttempt-user-1",
			"authAttempt",
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
		$this->dropForeignKey('fk-authAttempt-user-1', 'authAttempt');	 
		$this->dropTable('authAttempt');
	}
}
