<?php
/**
*文章控制器
*/

namespace frontend\controllers;


use Yii;
use frontend\controllers\base\BaseController;
use frontend\models\PostForm;
use common\models\CatModel;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\PostExtendsModel;


class PostController extends BaseController 
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                	'*'      => ['post', 'get'],
                ],
            ],
        ];
    }


	public function actions()
    {
        return [
            'upload'=>[
                'class' => 'common\widgets\file_upload\UploadAction',    
                'config' => [
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}",
                ]
            ],
            'ueditor'=>[
            	'class' => 'common\widgets\ueditor\UeditorAction',
            	'config'=>[
               		//上传图片配置
                	'imageUrlPrefix' => "", 
                	'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", 
            ]],
        ];
    }

	/** 
	*文章列表
	*@return 文章列表页面
	*/
	public function actionIndex()
	{
		return $this->render('index');
	} 


	/**
	*创建文章
	*@return 创建文章页面
	*/
	public function actionCreate()
	{
		$model = new PostForm();
		//定义场景
		$model -> setScenario(PostForm::SCENARIOS_CREATE);
		if ($model -> load(Yii::$app -> request -> post()) && $model -> validate())
		{
			if (!$model -> create())
			{
				Yii::$app -> session -> setFlash('warning', $model -> _lastError);
			}
			else
			{
				return $this -> redirect(['post/view', 'id' => $model -> id]);
			}
		}

		//获取所有文章分类
		$cat   = CatModel::getAllCats();
		return $this->render('create', ['model' => $model, 'cat' => $cat]);
	}

	/**
	*文章详情
	*/
	public function actionView($id)
	{
		$model = new PostForm();
		$data = $model -> getViewByID($id);

        //处理浏览数
        $model = new PostExtendsModel();
        $model -> upCounter(['post_id' => $id], 'browser', 1);

		return $this -> render('view', ['data' => $data]);
	}


}