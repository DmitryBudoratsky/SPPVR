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
 * @property int $sex
 * @property string $policy
 * @property string $snils
 * @property string $address
 * @property string $anamnesis
 * @property int $chatId
 * @property int $createdAt
 * @property int $updatedAt
 * @property string $verdict
 * @property int $verdictAt
 * @property int $verdictAuthorId
 *
 * @property Chat $chat
 * @property User $verdictAuthor
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
            [['status', 'birthDate', 'sex', 'chatId', 'createdAt', 'updatedAt', 'verdictAt', 'verdictAuthorId'], 'integer'],
            [['anamnesis', 'verdict'], 'string'],
            [['patientName', 'address'], 'string', 'max' => 255],
            [['policy'], 'string', 'max' => 16],
            [['snils'], 'string', 'max' => 14],
            [['chatId'], 'unique'],
            [['chatId'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::className(), 'targetAttribute' => ['chatId' => 'chatId']],
            [['verdictAuthorId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['verdictAuthorId' => 'userId']],
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
            'sex' => 'Sex',
            'policy' => 'Policy',
            'snils' => 'Snils',
            'address' => 'Address',
            'anamnesis' => 'Anamnesis',
            'chatId' => 'Chat ID',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'verdict' => 'Verdict',
            'verdictAt' => 'Verdict At',
            'verdictAuthorId' => 'Verdict Author ID',
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
    public function getVerdictAuthor()
    {
        return $this->hasOne(User::className(), ['userId' => 'verdictAuthorId']);
    }
}
