<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Chinhanh */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chinhanh-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'chinhanh_ten')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'chinhanh_diachi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'chinhanh_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'chinhanh_sodienthoai')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'chinhanh_ngaythanhlap')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
