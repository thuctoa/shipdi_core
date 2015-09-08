<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\AuthItem;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\AuthAssignment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-assignment-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php 
        if($model->user_id){
            echo $form   ->field($model, 'user_id')
                ->dropDownList(ArrayHelper::map(User::find()
                ->select(['username','displayname','id'])->where(['id'=>$model->user_id])->all(), 'id', 'displayname'),['class' => 'form-control inline-block']);
        }else{
    ?>
    <?= $form   ->field($model, 'user_id')
                ->dropDownList(ArrayHelper::map(User::find()
                ->select(['username','displayname','id'])->all(), 'id', 'displayname'),['class' => 'form-control inline-block']); ?>
    <?php
        }
    ?>
    <?= $form   ->field($model, 'item_name')
                ->dropDownList(ArrayHelper::map(AuthItem::find()->select(['name','type','description'])
                ->where(['type'=>'1'])->all(), 'name', function($data){return Yii::t('app',$data->name);}),['class' => 'form-control inline-block']); ?>
    
    <?= $form   ->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
