<?php

namespace common\models\db;

use common\components\helpers\TimeHelper;

class Incident extends BaseIncident
{
    const STATUS_CREATED = 0;
    const STATUS_FINISHED = 1;
    const STATUS_CREATED_LABEL = 'Создан';
    const STATUS_FINISHED_LABEL = 'Вынесен вердикт';

    public $birthDateString;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['patientName', 'birthDateString', 'anamnesis'], 'required'],
            [['patientName', 'anamnesis', 'verdict'], 'trim'],
            [['verdict'], 'required', 'when' => function (self $model) {
                return $model->status == self::STATUS_FINISHED;
            }],
            [['birthDateString'], 'date', 'format' => 'php:d.m.Y'],
        ]);
    }

    /**
     * Форматирование перед одиночным сохранением
     * @param bool $insert
     * @return bool
     * @throws
     */
    public function beforeSave($insert): bool
    {
        $result = parent::beforeSave($insert);

        if (!$result) {
            return false;
        }

        if (!empty($this->birthDateString)) {
            $formatter = \Yii::$app->formatter;
            $this->birthDate = $formatter->asTimestamp($this->birthDateString);
            $today = $formatter->asTimestamp($formatter->asDate(time(), 'php:d-m-Y'));

            if ($this->birthDate > $today) {
                $this->addError('birthDateString', 'Нельзя выбрать позднее сегодняшнего дня');
                return false;
            }
        }

        return true;
    }

    public function afterFind()
    {
        parent::afterFind();

        if (!is_null($this->birthDate)) {
            $this->birthDateString = \Yii::$app->formatter->asDate($this->birthDate, 'php:d.m.Y');
        }
    }

    public function attributeLabels()
    {
        return [
            'incidentId' => 'ID Случая',
            'status' => 'Статус',
            'patientName' => 'ФИО пациента',
            'birthDate' => 'Дата рождения',
            'birthDateString' => 'Дата рождения',
            'policy' => 'Полис',
            'snils' => 'Снилс',
            'address' => 'Адрес',
            'anamnesis' => 'Анамнез',
            'verdict' => 'Вердикт',
            'chatId' => 'Chat ID',
            'fileId' => 'File ID',
            'createdAt' => 'Дата создания',
            'updatedAt' => 'Дата обновления',
        ];
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_CREATED => self::STATUS_CREATED_LABEL,
            self::STATUS_FINISHED => self::STATUS_FINISHED_LABEL
        ];
    }

    /**
     * Получение отчёта по инциденту в виде одномерного массива
     * @return array<int|string>
     * @throws \yii\base\InvalidConfigException
     */
    public function getAsReportArray(): array
    {
        return [
            "Случай №$this->incidentId",
            $this->getAttributeLabel('incidentId') => $this->incidentId,
            $this->getAttributeLabel('status') => self::statusLabels()[$this->status],
            $this->getAttributeLabel('patientName') => $this->patientName,
            $this->getAttributeLabel('birthDateString') => $this->birthDateString,
            $this->getAttributeLabel('policy') => $this->policy,
            $this->getAttributeLabel('snils') => $this->snils,
            $this->getAttributeLabel('address') => $this->address,
            $this->getAttributeLabel('anamnesis') => $this->anamnesis,
            $this->getAttributeLabel('verdict') => $this->verdict,
            $this->getAttributeLabel('createdAt') => \Yii::$app->formatter->asDate($this->createdAt, 'php:d.m.Y H:i'),
            $this->getAttributeLabel('updatedAt') => \Yii::$app->formatter->asDate($this->updatedAt, 'php:d.m.Y H:i'),
            '© ' . \Yii::$app->name . ' ' . \Yii::$app->formatter->asDate(time(), 'php:Y')
        ];
    }
}
