<?php
namespace app\models;

use app\models\User;
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
    public $password_repeat;
    public $chinhanh_ma;
    public $displayname;
    public $que;
    public $sodienthoai;
    public $sothich;
    public $ngaysinh;
    public $diachi;
    public $role;
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email address'),
            'password' => Yii::t('app', 'Password'),
            'password_repeat' => Yii::t('app', 'Repeat Password'),
            'chinhanh_ma'=>Yii::t('app', 'Branch'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['role', 'string', 'max' => 64],
            [['displayname','que','sodienthoai','sothich','ngaysinh','diachi'],'string', 'max' => 512],
            
            [['displayname','que','sodienthoai','sothich','ngaysinh','diachi'],'required'],
            
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => Yii::t('app','This username has already been taken.')],
            ['username', 'string', 'min' => 4, 'max' => 20],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => Yii::t('app','This email address has already been taken.')],

            [['password','password_repeat'], 'required'],
            [['password','password_repeat'], 'string', 'min' => 6],
            [['password'], 'in', 'range'=>['password','Password','Password123','123456','12345678','letmein','monkey'] ,'not'=>true, 'message'=>Yii::t('app', 'You cannot use any really obvious passwords')],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message' => Yii::t("app", "The passwords must match")],
            [['chinhanh_ma'], 'exist', 'targetClass'=>'\app\models\Chinhanh', 'targetAttribute'=>'chinhanh_ma', 'message'=>Yii::t('app','This chinhanh doesn\'t exist')],
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
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->chinhanh_ma=$this->chinhanh_ma;
            $user->displayname=  $this->displayname;
            $user->que=  $this->que;
            $user->sothich=  $this->sothich;
            $user->sodienthoai=  $this->sodienthoai;
            $user->ngaysinh = date("Y-m-d", strtotime($this->ngaysinh));
            $user->diachi=  $this->diachi;
            
            $user->save();
           
            return $user;
        }

        return null;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChinhanh()
    {
        return $this->hasOne(Branch::className(), ['id' => 'chinhanh_ma']);
    }
}
