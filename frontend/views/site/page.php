<?php

/* @var $this yii\web\View */
/* @var $page common\models\db\Page */

$this->title = $page->title;

$this->registerCssFile("@web/css/page.css", [
], 'page');
?>
<div class="site-index">

	<h1 class="text-center"><?= $page->title ?></h1>

	<br />

	<?= $page->text ?>
</div>
