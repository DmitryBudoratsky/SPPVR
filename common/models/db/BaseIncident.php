<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "incident".
 *
 * @property int $incidentId
 * @property int $status
 * @property string $patientName
 * @property int $birthDate
 * @property string $policy
 * @property string $snils
 * @property string $address
 * @property string $anamnesis
 * @property int $chatId
 * @property int $fileId
 * @property int $createdAt
 * @property int $updatedAt
 * @property string $verdict
 *
 * @property Chat $chat
 * @property File $file
 */
class BaseIncident extends \common\models\db\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incident';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'birthDate', 'chatId', 'fileId', 'createdAt', 'updatedAt'], 'integer'],
            [['anamnesis', 'verdict'], 'string'],
            [['patientName', 'address'], 'string', 'max' => 255],
            [['policy'], 'string', 'max' => 16],
            [['snils'], 'string', 'max' => 14],
            [['chatId'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::className(), 'targetAttribute' => ['chatId' => 'chatId']],
            [['fileId'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['fileId' => 'fileId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'incidentId' => 'Incident ID',
            'status' => 'Status',
            'patientName' => 'Patient Name',
            'birthDate' => 'Birth Date',
            'policy' => 'Policy',
            'snils' => 'Snils',
            'address' => 'Address',
            'anamnesis' => 'Anamnesis',
            'chatId' => 'Chat ID',
            'fileId' => 'File ID',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'verdict' => 'Verdict',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chat::className(), ['chatId' => 'chatId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::className(), ['fileId' => 'fileId']);
    }
}
