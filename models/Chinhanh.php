<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chinhanh".
 *
 * @property integer $chinhanh_ma
 * @property string $chinhanh_ten
 * @property string $chinhanh_diachi
 * @property string $chinhanh_email
 * @property string $chinhanh_sodienthoai
 * @property string $chinhanh_ngaythanhlap
 */
class Chinhanh extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chinhanh';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chinhanh_ten', 'chinhanh_diachi', 'chinhanh_email', 'chinhanh_sodienthoai', 'chinhanh_ngaythanhlap'], 'required'],
            [['chinhanh_ngaythanhlap'], 'safe'],
            [['chinhanh_ten', 'chinhanh_diachi', 'chinhanh_email', 'chinhanh_sodienthoai'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'chinhanh_ma' => 'Chinhanh Ma',
            'chinhanh_ten' => 'Chinhanh Ten',
            'chinhanh_diachi' => 'Chinhanh Diachi',
            'chinhanh_email' => 'Chinhanh Email',
            'chinhanh_sodienthoai' => 'Chinhanh Sodienthoai',
            'chinhanh_ngaythanhlap' => 'Chinhanh Ngaythanhlap',
        ];
    }
    public function getChinhanh(){
        return $this->chinhanh_ten;
    }
}
