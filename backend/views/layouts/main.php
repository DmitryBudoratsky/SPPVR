<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* @var bool $isFullsize */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use common\widgets\Alert;

use backend\compenents\helpers\multitype\PostMultitypeModelViewHelper;
use backend\compenents\helpers\multitype\CommentMultitypeModelViewHelper;
use common\models\db\Chat;

AppAsset::register($this);
//DialogAsset::register($this);
\kartik\dialog\Dialog::widget();
$isFullsize = isset($isFullsize) ? $isFullsize : false;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <?php
    $this->registerJs("
        $(document).on('click', '.pjax-delete-link', function(e) {
            e.preventDefault();
            var deleteUrl = $(this).attr('delete-url');
            var pjaxContainer = $(this).attr('pjax-container');
            if ($(this).attr('confirm')) var confirm = $(this).attr('confirm');
            krajeeDialog.confirm(confirm, function (result) {
                if (result) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'post',
                        error: function(xhr, status, error) {
                            BootstrapDialog.show({
                                type: BootstrapDialog.TYPE_DANGER,
                                title: 'Ошибка запроса.',
                                message: xhr.responseText,
                                buttons: [{
                                    label: 'Ок',
                                    action: function(dialogItself){
                                        dialogItself.close();
                                    }
                                }]
                            });
                        }
                    }).done(function(data) {
                        $.pjax.reload('#' + $.trim(pjaxContainer), {timeout: 3000});
                        });
                }
            });
        });
    ");
    ?>

</head>
<body>
<?php $this->beginBody() ?>

<?php $containerClass = (isset($isFullsize) && $isFullsize) ? 'container-fluid' : 'container'; ?>

<!--существующие таблицы -->
<?php $existTablesArr = \Yii::$app->db->schema->getTableNames() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => \Yii::$app->name,
        'brandUrl' => \Yii::$app->homeUrl,
        'brandOptions' => false,
        'options' => [
            'class' => 'fixed-top navbar-inverse navbar-expand-lg navbar-dark bg-dark',
        ],
    	'innerContainerOptions' => [
    		'class' => $isFullsize ? 'container-fluid' : 'container',
    	],
        'collapseOptions' => [
            'class' => ['collapse', 'navbar-collapse', 'justify-content-end'],
            'id' => 'navbarCollapse',
        ],
    ]);
    $menuItems = [];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $devItems = [
            ['label' => 'Gii', 'url' => ['/gii']],
            ['label' => 'Admin Debug Panel', 'url' => \Yii::$app->urlManager->baseUrl . '/debug'],
            ['label' => 'Api Debug Panel', 'url' => \Yii::$app->frontendUrlManager->baseUrl . '/debug'],
        ];

        $menuItems[] = ['label' => 'Dev', 'items' => $devItems];

        $menuItems[] = ['label' => 'Пользователи', 'url' => ['user/index']];

        $menuItems[] = ['label' => 'Случаи', 'url' => ['incident/index']];
    }
    echo Nav::widget([
		'items' => $menuItems,
        'activateParents' => false,
		'encodeLabels' => false,
        'options' => ['class' => 'navbar-nav'],
    ]);
    NavBar::end();
    ?>

	<div class="<?= $containerClass ?>">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer fixed-bottom">
    <div class="<?= $containerClass ?>">
        <p class="float-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="float-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
