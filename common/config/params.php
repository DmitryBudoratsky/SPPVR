<?php
return [
	
	// Base
	
    'adminEmail' => 'admin@example.com',
    'communityEmail' => 'test-testmailer@yandex.ru',
    'user.passwordResetTokenExpire' => 3600,
	
	// адрес технической поддержки
	'supportUrl' => '<!!! add support url !!!>',
	
	// админ по умолчанию
	'adminDefaultEmail' => 'admin@ya.ru',
	'testAdminDefaultEmail' => 'testadmin@ya.ru',
	'adminDefaultPassword' => '123456',
	
	// отправка смс
	'codeLength' => 5,
	'maxCountSmsOnNumber' => 5,
	'timeIntervalBetweenSendingSms' => 10,
	'timeIntervalBetweenForMaxCountSmsOnNumber' => 1800,
    'smsSuffix' => 'eChSknv++sj',

    // код для авторизации
    // change to false for realease
    'useTokenStub' => true,
    'tokenStub' => '1234',
	
	// социальные сети
	
	// картинки
	'maxImagePreviewWidth' => 360,
	'maxImagePreviewHeight' => 360,
	
	// лимит записей при порционной загрузке
	'limitRecordsOnPage' => 10,
	
	// время действия кэширования
	'cacheExpirationTime' => 30 * 24 * 60 * 60,

	// количество секунд до повторной попытки авторизации при привышении количества попыток
	'timeIntervalBetweenUserAuthWithMultipleAttempts' => 5 * 60,
	// количество попыток авторизации
	'userAuthAttemptsLimitBeforeDelay' => 10,

    'maxFileSize' => 100000000,

    'webSocketPublicAddress' => 'ws://82.146.53.185:8305',
];
