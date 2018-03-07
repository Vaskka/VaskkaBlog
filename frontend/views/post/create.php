<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use frontend\widgets\hot\HotWidget;
use frontend\widgets\tags\TagWidget;


$this -> title = Yii::t('common', 'CreatePost');

$this -> params['breadcrumbs'][] = ['label' => Yii::t('common', 'Post'), 'url' => ['post/index']];

$this -> params['breadcrumbs'][] = $this->title;
?>

<div class="row">
	<!--主体-->
	<div class="col-lg-9">
		<div class="panel-title box-title">
			<span>创建文章</span>
		</div>

		<div class="pannel-body">
			<?php $form = ActiveForm::begin() ?>

			<!-- 编辑标题 -->
			<?=$form -> field($model, 'title') -> textinput(['maxlength' => true])?>

			<!-- 选择分类 -->
			<?=$form -> field($model, 'cat_id') -> dropDownList($cat) ?>
			
			<!-- 选择封面图 -->
			<?=$form->field($model, 'label_img') -> widget('common\widgets\file_upload\FileUpload',['config'=>[]]) ?>

			<!--编辑正文-->
			<?= $form->field($model, 'content') -> widget('common\widgets\ueditor\Ueditor',[
    			'options'=>[
    		    	'initialFrameHeight' => 400,
  				]]) ?>

  			<!-- 编辑标签 -->
			<?=$form -> field($model, 'tag') -> widget('common\widgets\tags\TagWidget') ?>

 			<div class="form-group">
 				<?=Html::submitButton(Yii::t('common', 'Release'), ['class' => 'btn btn-success'])?>
 			</div>

			<?php $form = ActiveForm::end() ?>
		</div>
	</div>

	<!--右侧-->
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