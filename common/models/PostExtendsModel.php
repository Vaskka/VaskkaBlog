<?php

namespace common\models;

use Yii;
use common\models\base\BaseModel;
/**
 * This is the model class for table "post_extends".
 *
 * @property integer $id
 * @property integer $post_id
 * @property integer $browser
 * @property integer $collect
 * @property integer $praise
 * @property integer $comment
 */
class PostExtendsModel extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_extends';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'browser', 'collect', 'praise', 'comment'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'post_id' => Yii::t('common', 'Post ID'),
            'browser' => Yii::t('common', 'Browser'),
            'collect' => Yii::t('common', 'Collect'),
            'praise' => Yii::t('common', 'Praise'),
            'comment' => Yii::t('common', 'Comment'),
        ];
    }


    /**
    *处理浏览数
    *@param 查询条件两个主键的映射
    *@param 自增属性
    *@param 自增数目
    */
    public function upCounter($condition, $attribute, $num)
    {
        $res = $this -> findOne($condition);
        if (!$res)
        {
            $this -> setAttributes($condition);
            $this -> browser = $num;
            $this -> save();
        }
        else
        {
            $counterData[$attribute] = $num;
            $res -> updateCounters($counterData);
        }
    }

}
