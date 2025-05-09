<?php

namespace console\migrations\settings;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%settings}}`.
 */
class m210624_101349_create_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%settings}}', [
            'settingsId' => $this->primaryKey(),
            'adminEmail' => $this->string(),
            'supportEmail' => $this->string(),
            'communityEmail' => $this->string(),
            'userPasswordResetTokenExpire' => $this->integer(),
            'appSupportEmail' => $this->string(),
            'appStoreUrl' => $this->string(),
            'googlePlayUrl' => $this->string(),
            'supportUrl' => $this->string(),
            'adminDefaultEmail' => $this->string(),
            'testAdminDefaultEmail' => $this->string(),
            'adminDefaultPassword' => $this->string(),
            'codeLength' => $this->integer(),
            'maxCountSmsOnNumber' => $this->integer(),
            'timeIntervalBetweenSendingSms' => $this->integer(),
            'timeIntervalBetweenForMaxCountSmsOnNumber' => $this->integer(),
            'smsSuffix' => $this->string(),
            'useTokenStub' => $this->boolean(),
            'tokenStub' => $this->string(),
            'faceBookAppId' => $this->string(),
            'twitterConsumerKey' => $this->string(),
            'twitterConsumerSecret' => $this->string(),
            'maxImagePreviewWidth' => $this->integer(),
            'maxImagePreviewHeight' => $this->integer(),
            'limitRecordsOnPage' => $this->integer(),
            'cacheExpirationTime' => $this->integer(),
            'googleApiKey' => $this->string(),
            'timeIntervalBetweenUserAuthWithMultipleAttempts' => $this->integer(),
            'userAuthAttemptsLimitBeforeDelay' => $this->integer(),
            'inAppPurchases' =>$this->json(),
            'userChatBlocking' =>$this->json(),
            'maxFileSize' => $this->integer(),
            'textShareForPromoCode' => $this->string(),
            'bonusForAuthorPromoCode' => $this->integer(),
            'bonusForUserPromoCode' => $this->integer(),
            'organizationSearchRadius' => $this->integer(),
            'externalApiAccessKeys' => $this->json(),
            'webSocketPublicAddress' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%settings}}');
    }
}
