#!/bin/bash

# User
php yii gii/model --tableName=user									--modelClass=BaseUser					--baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0
php yii gii/model --tableName=passwordResetRequest					--modelClass=BasePasswordResetRequest	--baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0
php yii gii/model --tableName=accessToken							--modelClass=BaseAccessToken			--baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0

# Chat
php yii gii/model --tableName=userMessage							--modelClass=BaseUserMessage			--baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0
php yii gii/model --tableName=message								--modelClass=BaseMessage				--baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0
php yii gii/model --tableName=chatMember							--modelClass=BaseChatMember				--baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0
php yii gii/model --tableName=chat									--modelClass=BaseChat					--baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0

# File
php yii gii/model --tableName=file									--modelClass=BaseFile					--baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0
php yii gii/model --tableName=imageFile								--modelClass=BaseImageFile				--baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0
php yii gii/model --tableName=videoFile								--modelClass=BaseVideoFile				--baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0
php yii gii/model --tableName=audioFile								--modelClass=BaseAudioFile				--baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0