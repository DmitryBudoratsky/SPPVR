<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');


Yii::setAlias('@uploads', '@frontend/web/uploads');
Yii::setAlias('@uploadsVideoFiles', '@frontend/web/uploads/video');
Yii::setAlias('@uploadsVideoFilesPreviewImages', '@frontend/web/uploads/video/preview');

Yii::setAlias('@iconsUploads', '@uploads/icons');



Yii::setAlias('@webUploads', 'uploads');
Yii::setAlias('@webIconsUploads', '@webUploads/icons');
Yii::setAlias('@webVideoUploads', '@webUploads/video');
Yii::setAlias('@webVideoUploadsPreviewImages', '@webVideoUploads/preview');

Yii::setAlias('@webFilesUploads', '@webUploads');
Yii::setAlias('@filesUploads', '@uploads');

Yii::setAlias('@frontendWeb', '@frontend/web');

