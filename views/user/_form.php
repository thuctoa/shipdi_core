<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\AuthItem;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'role')->textInput();?>
    <?php
                    if(Yii::$app->user->can('3. Human Resources Management')){
                        echo $form->field($model, 'role')
                                ->dropDownList(ArrayHelper::map(AuthItem::find()
                                ->select(['name','type','created_at'])->where(['type'=>'1'])
                                ->offset(3)
                                ->orderBy(['name' => SORT_DESC])
                                ->all(), 'name','role'),['class' => 'form-control inline-block']); 
                    }else if(Yii::$app->user->can('2. Branch Director')){
                        echo $form->field($model, 'role')
                                ->dropDownList(ArrayHelper::map(AuthItem::find()
                                ->select(['name','type','created_at'])->where(['type'=>'1'])
                                ->offset(2)
                                ->orderBy(['name' => SORT_DESC])
                                ->all(), 'name','role'),['class' => 'form-control inline-block']); 
                    }else if(Yii::$app->user->can('1. Director')){
                        echo $form->field($model, 'role')
                                ->dropDownList(ArrayHelper::map(AuthItem::find()
                                ->select(['name','type','created_at'])->where(['type'=>'1'])
                                ->offset(0) 
                                ->orderBy(['name' => SORT_DESC])
                                ->all(), 'name','role'),['class' => 'form-control inline-block']); 
                    }
    ?>
    <?php //echo $form->field($model, 'status')->textInput() ;?>

    <select id="user-status" name="User[status]">
        <option value="10" <?php if( $model->status==10) echo "selected"?>>Hoạt động</option>
        <option value="1"<?php if( $model->status==1) echo "selected"?>>Đang chờ kích hoạt</option>
        <option value="0" <?php if( $model->status==0) echo "selected"?>>Cấm hoạt động</option>
    </select>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
