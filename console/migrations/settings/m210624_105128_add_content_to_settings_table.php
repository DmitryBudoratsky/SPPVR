<?php

namespace console\migrations\settings;

use yii\db\Migration;

/**
 * Class m210624_105128_add_content_to_settings_table
 */
class m210624_105128_add_content_to_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('settings', array(
            'adminEmail' => 'admin@example.com',
            'supportEmail' => 'mailer@whitetigersoft.ru',
            'communityEmail' => 'test-testmailer@yandex.ru',
            'userPasswordResetTokenExpire' => 3600,
            'appSupportEmail' => 'support@whitetigersoft.ru',
            'appStoreUrl' => 'https://itunes.apple.com/ru/app/facebook/id284882215?mt=8',
            'googlePlayUrl' => 'https://play.google.com/store/apps/details?id=com.facebook.katana',
            'supportUrl' => '<!!! add support url !!!>',
            'adminDefaultEmail' => 'admin@ya.ru',
            'testAdminDefaultEmail' => 'testadmin@ya.ru',
            'adminDefaultPassword' => '123456',
            'codeLength' => 5,
            'maxCountSmsOnNumber' => 5,
            'timeIntervalBetweenSendingSms' => 10,
            'timeIntervalBetweenForMaxCountSmsOnNumber' => 1800,
            'smsSuffix' => 'eChSknv++sj',
            'useTokenStub' => true,
            'tokenStub' => '1234',
            'faceBookAppId' => '2041249009316477',
            'twitterConsumerKey' => 'mlUjzhc6P96i0ascObcwabyoX',
            'twitterConsumerSecret' => '441alDx5KOQF5wT8UTFoHCTg9wJnfQHU2ESFOExWmpPdeUvgct',
            'maxImagePreviewWidth' => 360,
            'maxImagePreviewHeight' => 360,
            'limitRecordsOnPage' => 10,
            'cacheExpirationTime' => 30 * 24 * 60 * 60,
            'googleApiKey' => 'AIzaSyAQPnocflT5SFVh1lQnlpWFSAhfdMUwxsM',
            'timeIntervalBetweenUserAuthWithMultipleAttempts' => 5 * 60,
            'userAuthAttemptsLimitBeforeDelay' => 10,
            'inAppPurchases' => json_encode([]),
            'userChatBlocking' => json_encode([["userChatBlockingId" => 1, "userChatBlockingTitle" => "Заблокировать на 1 час", "duration"=> 1 * 60 * 60,], ["userChatBlockingId" => 2, "userChatBlockingTitle" => "Заблокировать на 1 день", "duration"=> 24 * 60 * 60,], ["userChatBlockingId" => 3, "userChatBlockingTitle" => "Заблокировать навсегда", "duration"=> 1000 * 365 * 24 * 60 * 60,],]),
            'maxFileSize' => 100000000,
            'textShareForPromoCode' => 'Регистрируйтесь с моим промокодом: ',
            'bonusForAuthorPromoCode' => 5,
            'bonusForUserPromoCode' => 10,
            'organizationSearchRadius' => 200 * 1000,
            'externalApiAccessKeys' => json_encode(['dpP1FqC5XC05yOkO', '4PoTxCw0R0fscukm', 'K3cZ9U43QFl9Czf9']),
            'webSocketPublicAddress' => 'ws://82.146.53.185:8305',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210624_105128_add_content_to_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
