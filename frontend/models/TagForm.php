<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\TagModel;
/**
*标签的表单模型
*/
class TagForm extends Model
{
	public $id;

	public $tags;

	public function rules()
	{
		return [
			['tags', 'required'],
			['tags', 'each', 'rule' => ['string']],
		];
	}

	public function saveTags()
	{
		$ids = [];
		if (!empty($this -> tags))
		{
			foreach ($this -> tags as $tag) {
				$ids[] = $this -> _saveTag($tag);
			}

		}

		return $ids;
	}

	/**
	*
	*/
	private function _saveTag($tag)
	{
		$model = new TagModel();
		$res = $model -> find() -> where(['tag_name' => $tag]) -> one();
		if (!$res)
		{
			//如果未查到tag，说明第一次加，则创建这个tag且文章数目为1
			$model -> tag_name = $tag;
			$model -> post_num = 1;
			if (!$model -> save())
			{
				throw new \Exception(Yii::t('common', 'SaveTagError'));
			}
			return $model -> id;
		}
		else
		{
			//能查到就将文章数目递增1
			$res -> updateCounters(['post_num' => 1]);
		}

		return $res -> id;
	}
}