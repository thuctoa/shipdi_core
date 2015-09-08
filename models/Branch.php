<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%branch}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $phone_number
 * @property string $create_at
 * @property string $update_at
 *
 * @property User[] $users
 */
class Branch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%branch}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address', 'phone_number', 'create_at', 'update_at'], 'required'],
            [['create_at', 'update_at'], 'safe'],
            [['name', 'address'], 'string', 'max' => 2048],
            [['phone_number'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'address' => Yii::t('app', 'Address'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'create_at' => Yii::t('app', 'Create At'),
            'update_at' => Yii::t('app', 'Update At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['branch_id' => 'id']);
    }
}
