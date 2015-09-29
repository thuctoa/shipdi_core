<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'chinhanh_ma') ?>

    <?= $form->field($model, 'username') ?>

    <?php //echo $form->field($model, 'auth_key') ?>

    <?= $form->field($model, 'displayname') ?>

    <?php // echo $form->field($model, 'password_hash') ?>

    <?php // echo $form->field($model, 'password_reset_token') ?>

    <?php  echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'role') ?>

    <?php  echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php  echo $form->field($model, 'que') ?>

    <?php  echo $form->field($model, 'sodienthoai') ?>

    <?php  echo $form->field($model, 'diachi') ?>

    <?php  echo $form->field($model, 'sothich') ?>

    <?php  echo $form->field($model, 'ngaysinh') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
