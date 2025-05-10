<?php
return [
	'adminEmails' => ['admin@ya.ru', 'testadmin@ya.ru'],
    'adminDefaultPass' => '123456',
	
	// картинки
	'maxImagePreviewWidth' => 360,
	'maxImagePreviewHeight' => 360,
	
	// лимит записей при порционной загрузке
	'limitRecordsOnPage' => 10,

	// количество секунд до повторной попытки авторизации при привышении количества попыток
	'timeIntervalBetweenUserAuthWithMultipleAttempts' => 5 * 60,
	// количество попыток авторизации
	'userAuthAttemptsLimitBeforeDelay' => 10,

    'maxFileSize' => 100000000,

    'webSocketPublicAddress' => 'ws://',
];
