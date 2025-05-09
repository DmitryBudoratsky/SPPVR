<?php



\frontend\assets\BankCardAttachStatusAsset::register($this);

/* @var $this yii\web\View */
/* @var $bankCard common\models\db\BankCard */
/* @var $text string */

?>

<div class="thank-you">

    <h1><? echo $text; ?></h1>


    <input type="hidden" id="attachStatus" value="<?= $bankCard->status ?>">

</div>