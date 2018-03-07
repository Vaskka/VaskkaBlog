<?php
namespace frontend\models;

use yii\db\Query;
use yii\base\Model;
use yii;
use common\models\PostModel;
use common\models\RelationPostTagsModel;
use frontend\models\TagForm;
/**
*文章表单模型
*/

class PostForm extends Model
{
	public $id;
	public $title;
	public $content;
	public $label_img;
	public $cat_id;
	public $tag;
	public $_lastError = "";

	/**
	*定义场景
	*SCENARIOS_CREATE 创建文章
	*SCENARIOS_UPDATE 更新文章
	*EVENT_AFTER_CREATE 创建文章后的事件
	*EVENT_AFTER_UPDATE 更新文章后的事件
	*/
	const SCENARIOS_CREATE = 'create';
	const SCENARIOS_UPDATE = 'update';
	const EVENT_AFTER_CREATE = 'eventAfterCreate';
	const EVENT_AFTER_UPDATE = 'eventAfterUpdate';


	/**
	*场景设置
	*@return 全部场景列表
	*@see    \yii\base\Model::scenarios()
	*/
	public function scenarios() 
	{
		$scenarios = [
			self::SCENARIOS_CREATE => ['title', 'content', 'label_img', 'cat_id', 'tag'],
			self::SCENARIOS_UPDATE => ['title', 'content', 'label_img', 'cat_id', 'tag'],
		];
		return array_merge(parent::scenarios(), $scenarios);
	}

	public function rules()
	{
		return [
			[['id', 'title', 'content', 'cat_id'], 'required'],
			[['id', 'cat_id'], 'integer'],
			['title', 'string', 'min' => 5, 'max' => 50],

		];
	}

	public function attributeLabels()
	{
		return [
			'id'        => Yii::t('common', 'PostID'),
			'title'     => Yii::t('common', 'PostTitle'),
			'content'   => Yii::t('common', 'PostContent'),
			'label_img' => Yii::t('common', 'LabelIMG'),
			'cat_id'    => Yii::t('common', 'CatID'),
			'tag_id'    => Yii::t('common', 'TagID')
		];
	}


	public static function getList($condition, $curPage = 1, $pageSize = 5, $orderBy = ['id' => SORT_DESC])
	{
		$model = new PostModel();

		//查询语句
		$select = ['id', 'title', 'summary', 'label_img', 'cat_id', 'user_id', 'user_name', 'is_valid',
						'created_at', 'updated_at'];
		$query = $model 
			-> find() 
			-> select($select) 
			-> where($condition) 
			-> with('relate.tag', 'extend')
			-> orderBy($orderBy);

		//获取分页数据
		$res = $model -> getPages($query, $curPage, $pageSize);
		//格式化
		$res['data'] = self::__formatList($res['data']);
		return $res;
	}


	/**
	*数据格式化
	*/
	public static function __formatList($data)
	{
		foreach ($data as $list) {
			$list['tag'] = [];
			if (isset($list['relate']) && !empty($list['relate']))
			{
				foreach ($list['relate'] as $lt) {
					$list['tag'][] = $lt['tag']['tag_name'];
				}
			}
			unset($list['relate']);
		}

		return $data;
	} 


	/**
	*创建文章
	*/
	public function create() 
	{
		//使用事物
		$transaction = Yii::$app -> db -> beginTransaction();
		try 
		{
			$model = new PostModel();

			$model -> setAttributes($this -> attributes);
			$model -> summary = $this -> getSummary();
			$model -> user_id = Yii::$app -> user -> identity -> id;
			$model -> user_name = Yii::$app -> user -> identity -> username;
			$model -> is_valid = PostModel::IS_VALID;
			$model -> created_at = time();
			$model -> updated_at = time();
			if (!$model -> save())
			{
				throw new \Exception(Yii::t('common', 'SavePostError'));
			}
			$this -> id = $model -> id;

			//调用创建文章后的事件
			$data = array_merge($this -> getAttributes(), $model -> getAttributes());
			$this -> _eventAfterCreate($data);

			$transaction -> commit();
			return true;
		} 
		catch (\Exception $e)
		{

			$transaction -> rollBack();
			$this -> _lastError = $e -> getMessage();
			return false;
		}
	}


	/**
	*使用post_id显示具体文章
	*@return 包含具体文章的dict
	*@param  具体文章的id $id
	*/
	public function getViewByID($id)
	{
		$res = PostModel::find() -> with('relate.tag', 'extend') -> where(['id' => $id]) -> asArray() -> one();
		if (!$res)
		{
			throw new NotFoundHttpException(Yii::t('common', 'PostNotExistError'));
		}


		//处理标签格式
		$res['tags'] = []; 
		if (isset($res['relate']) && !empty($res['relate']))
		{
			foreach ($res['relate'] as $list) {
				$res['tags'][] = $list['tag']['tag_name'];
			}
		}
		unset($res['relate']);

		return $res;

	}


	/**
	*截取正文形成摘要
	*@param  startIndex 摘要开始的索引
	*@param  endIndex   摘要结束的索引 
	*@param  charset    编码
	*@return str 形成的摘要
	*/
	private function getSummary($startIndex = 0, $endIndex = 50, $charset = 'utf-8')
	{
		if (empty($this -> content))
		{
			return null;
		}
		else
		{
			//正文不是空则返回去掉标签和空格的前五十个字符
			return mb_substr(str_replace('&nbsp;', '', strip_tags($this -> content)), $startIndex, $endIndex, $charset);
		}
	}

	/**
	*保存文章后的事件
	*@param 传入事件的数据
	*/
	public function _eventAfterCreate($data)
	{
		//订阅事件
		$this -> on(self::EVENT_AFTER_CREATE, [$this, '_eventAddTag'], $data);
		//触发事件
		$this -> trigger(self::EVENT_AFTER_CREATE);
	}

	/**
	*添加标签
	*@param 事件对象 $event
	*/
	public function _eventAddTag($event)
	{
		//保存标签
		$tag = new TagForm();
		$tag -> tags = $event -> data['tag'];
		$tagids = $tag -> saveTags();

		//删除原先关联
		RelationPostTagsModel::deleteAll(['post_id' => $event -> data['id']]);

		//批量保存文章标签的关联关系
		if (!empty($tagids)) 
		{
			foreach ($tagids as $key => $id) {
				$row[$key]['post_id'] = $this -> id;
				$row[$key]['tag_id']  = $id;
			}

			//批量插入
			$res = (new Query()) 
				-> createCommand() 
				-> batchInsert(RelationPostTagsModel::tableName(), ['post_id', 'tag_id'], $row)
				-> execute();
			//检验结果
			if (!$res)
			{
				throw new \Exception(Yii::t('common', 'RelationPostTagError'));
			}
		}
	}


}