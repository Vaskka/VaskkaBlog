<?php

namespace common\models;

/**
*文章模型
*/

use Yii;
use common\models\base\BaseModel;
use common\models\RelationPostTagsModel;
use common\models\PostExtendsModel;
/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property string $title
 * @property string $summary
 * @property string $content
 * @property string $label_img
 * @property integer $cat_id
 * @property integer $user_id
 * @property string $user_name
 * @property integer $is_valid
 * @property integer $created_at
 * @property integer $updated_at
 */
class PostModel extends BaseModel
{

    /**
    *1:已发布
    *2:未发布 
    */
    const IS_VALID  = 1;
    const NOT_VALID = 0; 

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['cat_id', 'user_id', 'is_valid', 'created_at', 'updated_at'], 'integer'],
            [['title', 'summary', 'label_img', 'user_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'title' => Yii::t('common', 'Title'),
            'summary' => Yii::t('common', 'Summary'),
            'content' => Yii::t('common', 'Content'),
            'label_img' => Yii::t('common', 'Label Img'),
            'cat_id' => Yii::t('common', 'Cat ID'),
            'user_id' => Yii::t('common', 'User ID'),
            'user_name' => Yii::t('common', 'User Name'),
            'is_valid' => Yii::t('common', 'Is Valid'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
        ];
    }

    /**
    *获得post_id与id的关联
    */
    public function getRelate()
    {
        return $this -> hasMany(RelationPostTagsModel::className(), ['post_id' => 'id']);
    }

    public function getExtend()
    {
        return $this -> hasOne(PostExtendsModel::className(), ['post_id' => 'id']);
    }
}
