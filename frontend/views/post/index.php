<?php

use frontend\widgets\post\PostWidget;
use yii\base\Widget;
use frontend\widgets\hot\HotWidget;
use frontend\widgets\tags\TagWidget;
?>

<div class="row">
	<div class="col-lg-9">
		<?= PostWidget::widget(); ?>
	</div>
	<div class="col-lg-3">
		<div class="panel">
			<div class="panel-title box-title">
				<h1>Never Stop!</h1>
			</div>
			<div class="panel-body">
				<button class="btn btn-success btn-block" onclick="javascrtpt:window.location.href='/post/create'">
					<?= Yii::t('common', 'WritePost') ?>
				</button>
			</div>
			<div class="panel-body">
					<?= HotWidget::widget(); ?>
			</div>
			<div class="panel-body">
					<?= TagWidget::widget(); ?>
			</div>
		</div>
	</div>
</div>