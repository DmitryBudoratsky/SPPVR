<?php

namespace console\migrations\settings;

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%settings}}`.
 */
class m210701_064617_drop_columns_from_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%settings}}', 'adminEmail');
        $this->dropColumn('{{%settings}}', 'supportEmail');
        $this->dropColumn('{{%settings}}', 'communityEmail');
        $this->dropColumn('{{%settings}}', 'userPasswordResetTokenExpire');
        $this->dropColumn('{{%settings}}', 'adminDefaultEmail');
        $this->dropColumn('{{%settings}}', 'testAdminDefaultEmail');
        $this->dropColumn('{{%settings}}', 'adminDefaultPassword');
        $this->dropColumn('{{%settings}}', 'maxCountSmsOnNumber');
        $this->dropColumn('{{%settings}}', 'timeIntervalBetweenSendingSms');
        $this->dropColumn('{{%settings}}', 'timeIntervalBetweenForMaxCountSmsOnNumber');
        $this->dropColumn('{{%settings}}', 'smsSuffix');
        $this->dropColumn('{{%settings}}', 'useTokenStub');
        $this->dropColumn('{{%settings}}', 'tokenStub');
        $this->dropColumn('{{%settings}}', 'faceBookAppId');
        $this->dropColumn('{{%settings}}', 'twitterConsumerKey');
        $this->dropColumn('{{%settings}}', 'twitterConsumerSecret');
        $this->dropColumn('{{%settings}}', 'maxImagePreviewWidth');
        $this->dropColumn('{{%settings}}', 'maxImagePreviewHeight');
        $this->dropColumn('{{%settings}}', 'cacheExpirationTime');
        $this->dropColumn('{{%settings}}', 'googleApiKey');
        $this->dropColumn('{{%settings}}', 'timeIntervalBetweenUserAuthWithMultipleAttempts');
        $this->dropColumn('{{%settings}}', 'userAuthAttemptsLimitBeforeDelay');
        $this->dropColumn('{{%settings}}', 'inAppPurchases');
        $this->dropColumn('{{%settings}}', 'maxFileSize');
        $this->dropColumn('{{%settings}}', 'bonusForAuthorPromoCode');
        $this->dropColumn('{{%settings}}', 'bonusForUserPromoCode');
        $this->dropColumn('{{%settings}}', 'organizationSearchRadius');
        $this->dropColumn('{{%settings}}', 'externalApiAccessKeys');
        $this->dropColumn('{{%settings}}', 'webSocketPublicAddress');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%settings}}', 'adminEmail', $this->string());
        $this->addColumn('{{%settings}}', 'supportEmail', $this->string());
        $this->addColumn('{{%settings}}', 'communityEmail', $this->string());
        $this->addColumn('{{%settings}}', 'userPasswordResetTokenExpire', $this->integer());
        $this->addColumn('{{%settings}}', 'adminDefaultEmail', $this->string());
        $this->addColumn('{{%settings}}', 'testAdminDefaultEmail', $this->string());
        $this->addColumn('{{%settings}}', 'adminDefaultPassword', $this->string());
        $this->addColumn('{{%settings}}', 'maxCountSmsOnNumber', $this->integer());
        $this->addColumn('{{%settings}}', 'timeIntervalBetweenSendingSms', $this->integer());
        $this->addColumn('{{%settings}}', 'timeIntervalBetweenForMaxCountSmsOnNumber', $this->integer());
        $this->addColumn('{{%settings}}', 'smsSuffix', $this->string());
        $this->addColumn('{{%settings}}', 'useTokenStub', $this->boolean());
        $this->addColumn('{{%settings}}', 'tokenStub', $this->string());
        $this->addColumn('{{%settings}}', 'faceBookAppId', $this->string());
        $this->addColumn('{{%settings}}', 'twitterConsumerKey', $this->string());
        $this->addColumn('{{%settings}}', 'twitterConsumerSecret', $this->string());
        $this->addColumn('{{%settings}}', 'maxImagePreviewWidth', $this->integer());
        $this->addColumn('{{%settings}}', 'maxImagePreviewHeight', $this->integer());
        $this->addColumn('{{%settings}}', 'cacheExpirationTime', $this->integer());
        $this->addColumn('{{%settings}}', 'googleApiKey', $this->string());
        $this->addColumn('{{%settings}}', 'timeIntervalBetweenUserAuthWithMultipleAttempts', $this->integer());
        $this->addColumn('{{%settings}}', 'userAuthAttemptsLimitBeforeDelay', $this->integer());
        $this->addColumn('{{%settings}}', 'inAppPurchases', $this->json());
        $this->addColumn('{{%settings}}', 'maxFileSize', $this->integer());
        $this->addColumn('{{%settings}}', 'bonusForAuthorPromoCode', $this->integer());
        $this->addColumn('{{%settings}}', 'bonusForUserPromoCode', $this->integer());
        $this->addColumn('{{%settings}}', 'organizationSearchRadius', $this->integer());
        $this->addColumn('{{%settings}}', 'externalApiAccessKeys', $this->json());
        $this->addColumn('{{%settings}}', 'webSocketPublicAddress', $this->string());
    }
}
