<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Chinhanh */

$this->title = $model->chinhanh_ma;
$this->params['breadcrumbs'][] = ['label' => 'Chinhanhs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chinhanh-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->chinhanh_ma], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->chinhanh_ma], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'chinhanh_ma',
            'chinhanh_ten',
            'chinhanh_diachi',
            'chinhanh_email:email',
            'chinhanh_sodienthoai',
            'chinhanh_ngaythanhlap',
        ],
    ]) ?>

</div>
