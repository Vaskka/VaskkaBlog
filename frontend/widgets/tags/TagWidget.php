<?php
namespace frontend\widgets\tags;

use Yii;
use yii\bootstrap\Widget;
use common\models\TagModel;


/**
* 标签云
*/
class TagWidget extends Widget
{
	public $title = '';

	public $limit = 20;


	public function run()
	{
		$res = TagModel::find()
			-> orderBy(['post_num' => SORT_DESC])
			-> limit($this -> limit)
			-> all();

		$result['title'] = $this -> title ? : Yii::t('common', 'TagsCloud');
		$result['body'] = $res ? : [];

		return $this -> render('index', ['data' => $result]);
	}
}


?>