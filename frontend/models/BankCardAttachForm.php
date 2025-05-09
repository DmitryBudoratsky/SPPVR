<?php

namespace frontend\models;

use common\models\db\BankCard;
use yii\base\Model;
use common\models\db\User;

/**
 * BankCardAttachForm form
 */
class BankCardAttachForm extends Model
{
    public $cardNumber;
    /** @var string $expired */
    public $expired;
    public $expiredMonth;
    public $expiredYear;

    /** @var User $user */
    public $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cardNumber'], 'required'],
            [['expiredMonth', 'expiredYear'], 'required'],
            ['cardNumber', 'string', 'length' => [19, 23]],
            ['expired', 'string', 'length' => 5],
            [['user'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cardNumber' => \Yii::t('app', 'Номер банковской карты'),
            'expired' =>  \Yii::t('app', 'Срок истечения'),
            'expiredMonth' =>  \Yii::t('app', 'Срок истечения'),
            'expiredYear' =>  \Yii::t('app', 'Срок истечения'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'cardNumber' => \Yii::t('app', 'от 16 до 19 цифр')
        ];
    }

    public function attach()
    {
        $bankCard = BankCard::find()
            ->andWhere(['bankCard.hash' => md5($this->cardNumber)])
            ->andWhere(['bankCard.status' => BankCard::STATUS_ACTIVE])
            ->one();

        if (!empty($bankCard)) {
            $this->addError('cardNumber', \Yii::t('app', 'Не удалось привязать карту. Данная карта уже привязана к аккаунту.'));
            return null;
        }

        $this->removeOldBankCards();

        /** @var BankCard $bankCard */
        $bankCard = new BankCard();
        $bankCard->userId = $this->user->userId;
        $bankCard->type = BankCard::TYPE_MIR;
        $bankCard->bankInfo = "bankInfo";
        $bankCard->bindingId = "bankInfo";
        $bankCard->cardNumber = $this->cardNumber;
        $bankCard->month = $this->expiredMonth;
        $bankCard->year = $this->expiredYear;
        $bankCard->hash = md5($this->cardNumber);
        if (!$bankCard->save()) {
            \Yii::trace("Bank card save errors: " . var_export($bankCard->getErrors(), true));
            return null;
        }

        return $bankCard;
    }

    public function removeOldBankCards()
    {
        $bankCards = $this->user->getBankCards();
        foreach ($bankCards->each() as $bankCard) {
            $bankCard->delete();
        }
    }

    public static function getMontsArray()
    {
        $arr = [];
        $firstDigit = '0';
        for ($second = 1; $second <= 9; $second++) {
            $arr[$firstDigit . $second] = $firstDigit . $second;
        }
        $firstDigit = '1';
        for ($second = 0; $second <= 2; $second++) {
            $arr[$firstDigit . $second] = $firstDigit . $second;
        }

        return $arr;
    }

    public static function getYearArray()
    {
        $arr = [];
        $currentYear = date('Y');

        for ($year = $currentYear; $year <= $currentYear + 5; $year++) {
            $arr[$year] = $year;
        }
        return $arr;
    }
}