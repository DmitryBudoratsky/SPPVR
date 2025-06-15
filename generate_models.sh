#!/bin/bash

# User
php yii gii/model --tableName=user --modelClass=BaseUser --baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0
php yii gii/model --tableName=accessToken	--modelClass=BaseAccessToken --baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0

# Incident
php yii gii/model --tableName=incident --modelClass=BaseIncident --baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0

# Chat
php yii gii/model --tableName=message --modelClass=BaseMessage --baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0
php yii gii/model --tableName=chat	--modelClass=BaseChat	--baseClass="common\models\db\base\BaseModel" --ns="common\models\db" --overwrite=1 --interactive=0