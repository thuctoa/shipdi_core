<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Chinhanh */

$this->title = 'Update Chinhanh: ' . ' ' . $model->chinhanh_ma;
$this->params['breadcrumbs'][] = ['label' => 'Chinhanhs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->chinhanh_ma, 'url' => ['view', 'id' => $model->chinhanh_ma]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="chinhanh-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
