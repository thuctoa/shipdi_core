<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Chinhanh;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

$this->title = Yii::t('app','Sign up');
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Yii::t("app", "Please fill out the following fields to sign up"); ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <fieldset><legend><?= Yii::t('app', 'User')?></legend>
                <?= $form->field($model, 'displayname') ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'sodienthoai') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'password_repeat')->passwordInput() ?>
                <?= $form->field($model, 'chinhanh_ma')
                    ->dropDownList(ArrayHelper::map(Chinhanh::find()
                    ->select(['chinhanh_ten','chinhanh_diachi','chinhanh_ma'])
                    ->all(), 'chinhanh_ma', 'chinhanh_ten'),['class' => 'form-control inline-block']); ?>
                
                <?= $form->field($model, 'diachi') ?>
                <?= $form->field($model, 'que') ?>
                <?= $form->field($model, 'ngaysinh')->widget(
                    DatePicker::className(), [
                        // inline too, not bad
                        'inline' => FALSE, 
                        // modify template for custom rendering
                        //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                        ]
                ]);
                ?>
                <?= $form->field($model, 'sothich') ?>
                

            </fieldset>
                <div class="form-group">
                    <?= Html::submitButton('Sign up', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
