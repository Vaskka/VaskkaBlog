<?php
namespace frontend\widgets\banner;

use Yii;
use yii\bootstrap\Widget;

class BannerWidget extends Widget
{
	public $items = [];

	public function init()
	{
		if (empty($this -> items))
		{
			$this -> items = [
				['label' => 'label_1',
				 'img_url' => '/static/images/banner/banner_1.jpg',
				 'url' => ['site/index'],
				 'active' => 'active',
				 'html' => ''],
				['label' => 'label_2',
				 'img_url' => '/static/images/banner/banner_2.jpg',
				 'url' => ['site/index'],
				 'active' => '',
				 'html' => ''],
				['label' => 'label_3',
				 'img_url' => '/static/images/banner/banner_3.jpg',
				 'url' => ['site/index'],
				 'active' => '',
				 'html' => ''],
			];
		}

	}


	public function run()
	{
		$data['items'] = $this -> items;  
		return $this -> render('index', ['data' => $data]); 
	}

}


?>