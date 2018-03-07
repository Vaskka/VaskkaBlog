<?php
namespace frontend\models;

use common\models\UserModel;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $rePassword;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\UserModel', 'message' => Yii::t('common', 'UserNameAlreadyToken')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            //使用正则表达式匹配正确的用户名
            ['username', 'match', 'pattern' => '/^(?!_|\d|\\\)[A-Za-z0-9\u4e00-\u9fa5_]+$/u', 'message' => Yii::t('common', 'UserNameFormat')],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\UserModel', 'message' => Yii::t('common', 'EmailAlreadyToken')],
            //密码需要与重复密码一致
            ['rePassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('common', 'PasswordShouldSame')],
            [['password', 'rePassword'], 'required'],
            [['password', 'rePassword'], 'string', 'min' => 6],
            ['verifyCode', 'captcha'],
        ];
    }


    /**
    *语言包映射
    *
    */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('common', 'UserName'),
            'email'   => Yii::t('common', 'UserEmail'),
            'password' => Yii::t('common', 'UserPassword'),
            'rePassword' => Yii::t('common', 'UserRePassword'),
            'verifyCode' => Yii::t('common', 'VerifyCode'),

        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new UserModel();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
