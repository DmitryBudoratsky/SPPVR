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

        // --- push уведомления ---
        if (in_array('pushNotification', $existTablesArr)) {
            $devItems [] = ['label' => 'Отправить PUSH', 'url' => ['push-notification/create']];
            $devItems [] = ['label' => 'Список PUSH', 'url' => ['push-notification/index']];
        }

        // Отслеживание координат
        if (in_array('geolocation', $existTablesArr)) {
            $devItems[] = ['label' => 'Отслеживание координат', 'url' => ['geolocation/index']];
        }

        $menuItems[] = ['label' => 'Dev', 'items' => $devItems];

        $menuItems[] = ['label' => 'Пользователи', 'url' => ['user/index']];

        // --- чаты ---
        if (in_array('chat', $existTablesArr)) {
        	$menuItems[] = ['label' => 'Чаты', 'items' => [
        		['label' => 'Персональные чаты', 'url' => ['chat/index', 'type' => Chat::TYPE_PERSONAL_CHAT]],
        		['label' => 'Групповые чаты', 'url' => ['chat/index', 'type' => Chat::TYPE_GROUP_CHAT]],
        	]];
        }

        // --- контент ---
        $contentItems = [];

        // ---о нас ---
        if (in_array('aboutUs', $existTablesArr)) {
            $contentItems[] = ['label' => 'О нас', 'url' => ['about-us/index']];
        }

        // --- страницы ---
        if (in_array('page', $existTablesArr)) {
            $contentItems[] = ['label' => 'Страницы', 'url' => ['page/index']];
        }
        // --- страны ---
        if (in_array('country', $existTablesArr)) {
            $contentItems[] = ['label' => 'Страны', 'url' => ['country/index']];
        }
        // --- города ---
        if (in_array('city', $existTablesArr)) {
            $contentItems[] = ['label' => 'Города', 'url' => ['city/index']];
        }
        // --- заявки ---
        if (in_array('request', $existTablesArr)) {
            $contentItems[] = ['label' => 'Заявки', 'url' => ['request/index']];
        }
        // --- Услуги ---
        if (in_array('service', $existTablesArr)) {
            $contentItems[] =  ['label' => 'Услуги', 'url' => ['service/index']];
        }

        // --- Марки автомобиля ---
        if (in_array('carBrand', $existTablesArr)) {
            $contentItems[] =  ['label' => 'Марки автомобиля', 'url' => ['vehicle-brand/index']];
        }

        // --- Автомобили ---
        if (in_array('vehicle', $existTablesArr)) {
            $contentItems[] =  ['label' => 'Автомобили', 'url' => ['vehicle/index']];
        }

        // --- Промо коды ---
        if (in_array('promoCode', $existTablesArr)) {
            $contentItems[] =  ['label' => 'Промо коды', 'url' => ['promo-code/index']];
        }
        if (in_array('promoCodeActivation', $existTablesArr)) {
            $contentItems[] =  ['label' => 'Активации промо кодов', 'url' => ['promo-code-activation/index']];
        }

        // --- Lms Курсы ---
        if (in_array('lmsCourse', $existTablesArr)) {
            $contentItems[] =  ['label' => 'Lms Курсы', 'url' => ['lms/lms-course']];
        }

        /** Quiz  */
        if (in_array('quiz', $existTablesArr)) {
            $contentItems[] =  ['label' => 'Quiz', 'url' => ['lms/quiz/index']];
        }

        // Уведомления
        if (in_array('notification', $existTablesArr)) {
            $contentItems[] = ['label' => 'Уведомления', 'url' => ['notification/index']];
        }

        $menuItems[] = ['label' => 'Контент', 'items' => $contentItems];

        $menuItems[] = [
            'label' => 'Выйти (' . Yii::$app->user->identity->name . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
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
