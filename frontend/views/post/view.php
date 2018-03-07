<?php
use frontend\widgets\hot\HotWidget;
use frontend\widgets\tags\TagWidget;
$this -> title = $data['title'];
$this -> params['breadcrumbs'][] = ['label' => Yii::t('common', 'Post'), 'url' => ['post/index']];
$this -> params['breadcrumbs'][] = $this -> title;

?>

<div class="row">
	<div class="col-lg-9">
		<div class="page-title">
			<!-- 文章标题 -->
			<h1>
				<?= $data['title'] ?>
			</h1>
			
			<!--文章信息-->
			<span>作者：<?= $data['user_name']; ?></span>
			<span>发布日期：<?= date('y-m-d', $data['created_at']); ?></span>
			<span>浏览：<?= isset($data['extend']['browser'])?$data['extend']['browser']:0 ?>次</span>
		</div>

		<!--文章正文-->
		<div class="page-content">
			<?= $data['content']; ?>
		</div>

		<!--文章标签-->
		<div class="page-tag">
			标签：
				<?php foreach ($data['tags'] as $tag): ?>
				<span><a class="label label-info" href="#"> <?= $tag ?> </a></span>
				<?php endforeach;?>
		</div>
	</div>
	<div class="col-lg-3">
		<!-- 小工具（待完善） -->


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