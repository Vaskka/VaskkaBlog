<?php
/**
*基础控制器
*/

namespace frontend\controllers\base;

use yii\web\Controller;

class BaseController extends Controller
{
	public function atferAction($action)
	{
		if (!parent::atferAction($action)) 
		{
			return false; 
		}
		return true;
	}
} 