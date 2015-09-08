<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property integer $id
 * @property double $x
 * @property double $y
 * @property integer $time
 * @property integer $session
 * @property string $date
 * @property string $idorder
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['x', 'y', 'session', 'date', 'idorder'], 'required'],
            [['x', 'y'], 'number'],
            [['time', 'session'], 'integer'],
            [['date'], 'safe'],
            [['idorder'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'x' => Yii::t('app', 'X'),
            'y' => Yii::t('app', 'Y'),
            'time' => Yii::t('app', 'Time'),
            'session' => Yii::t('app', 'Session'),
            'date' => Yii::t('app', 'Date'),
            'idorder' => Yii::t('app', 'Idorder'),
        ];
    }
}
