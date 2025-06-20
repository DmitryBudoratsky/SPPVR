<?php

namespace common\models\db;

use common\components\helpers\TimeHelper;

class Incident extends BaseIncident
{
    const STATUS_CREATED = 0;
    const STATUS_FINISHED = 1;
    const STATUS_CREATED_LABEL = 'Создан';
    const STATUS_FINISHED_LABEL = 'Вынесен вердикт';
    const SEX_MAN = 0;
    const SEX_WOMAN = 1;
    const SEX_MAN_LABEL = 'Мужской';
    const SEX_WOMAN_LABEL = 'Женский';

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

    public static function sexLabels()
    {
        return [
            self::SEX_MAN => self::SEX_MAN_LABEL,
            self::SEX_WOMAN => self::SEX_WOMAN_LABEL
        ];
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

        if ($this->isAttributeChanged('status') && $this->status == self::STATUS_FINISHED) {
            $this->verdictAuthorId = User::getUser()->userId;
            $this->verdictAt = time();
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
            'sex' => 'Пол',
            'status' => 'Статус',
            'patientName' => 'ФИО пациента',
            'birthDate' => 'Дата рождения',
            'birthDateString' => 'Дата рождения',
            'policy' => 'Полис',
            'snils' => 'Снилс',
            'address' => 'Адрес',
            'anamnesis' => 'Анамнез',
            'verdictAuthorId' => 'Автор вердикта',
            'verdictAuthor' => 'Автор вердикта',
            'verdict' => 'Вердикт',
            'verdictAt' => 'Дата и время вердикта',
            'chatId' => 'Chat ID',
            'fileId' => 'File ID',
            'createdAt' => 'Дата и время создания',
            'updatedAt' => 'Дата и время обновления',
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
    public function serialize(): array
    {
        $author = $this->verdictAuthor;

        return [
            'name' => "Случай №$this->incidentId",
            'incidentId' => $this->incidentId,
            'status' => self::statusLabels()[$this->status],
            'patientName' => $this->patientName,
            'sex' => self::sexLabels()[$this->sex],
            'birthDateString' => $this->birthDateString,
            'policy' => $this->policy,
            'snils' => $this->snils,
            'address' => $this->address,
            'anamnesis' => $this->anamnesis,
            'verdictAuthor' => $author->name . ' ' . $author->lastname . ' ' . $author->surname,
            'verdict' => $this->verdict,
            'verdictAt' => \Yii::$app->formatter->asDate($this->verdictAt, 'php:d.m.Y H:i'),
            'createdAt' => \Yii::$app->formatter->asDate($this->createdAt, 'php:d.m.Y H:i'),
            'updatedAt' => \Yii::$app->formatter->asDate($this->updatedAt, 'php:d.m.Y H:i'),
            'author' => '© ' . \Yii::$app->name . ' ' . \Yii::$app->formatter->asDate(time(), 'php:Y')
        ];
    }
}
