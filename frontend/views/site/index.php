<?php

/* @var $this yii\web\View */

use frontend\widgets\banner\BannerWidget;
use yii\base\Widget;
use frontend\widgets\chat\ChatWidget;
use frontend\widgets\post\PostWidget;

$this->title = Yii::t('common', 'MainPage');
?>

<div class="row">
    <div class="col-lg-2"></div>

    <div class="col-lg-8">
        <?= BannerWidget::widget();?>
        <div class="main-post">
            <?= PostWidget::widget();?>
        </div>
    </div>

    <div class="col-lg-2"></div>
</div>

