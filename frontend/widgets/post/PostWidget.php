<?php
namespace frontend\widgets\post;

use Yii;
use yii\base\Widget;
use common\models\PostModel;
use frontend\models\PostForm;
use yii\helpers\Url;
use yii\data\Pagination;

/**
* 文章列表的组件
*/
class PostWidget extends Widget
{

	/**
	*文章列表的标题
	*/
	public $title = '';

	/**
	*显示条数
	*/
	public $limit = 6;

	/**
	*是否显示更多
	*/
	public $more = true;

	/**
	*是否显示分页
	*/
	public $page = true;

	/**
	*
	*/
	public function run()
	{
		$curPage = Yii::$app -> request -> get('page', 1);

		//查询条件
		$condition = ['=', 'is_valid', PostModel::IS_VALID];
		$res = PostForm::getList($condition, $curPage, $this -> limit);
		
		$result['title'] = $this -> title?:Yii::t('common', 'NewPost');
		$result['more'] = Url::to(['post/index']);
		$result['body'] = $res['data']?:[];

		//是否显示分页
		if ($this -> page)
		{
			$pages = new Pagination(['totalCount' => $res['count'], 'pageSize' => $res['pageSize']]);
			$result['page'] = $pages;
		}

		return $this -> render('index', ['data' => $result]);
	}

	/**
	*
	*/







}


?>