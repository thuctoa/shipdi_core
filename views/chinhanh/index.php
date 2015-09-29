<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Chinhanhs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chinhanh-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Chinhanh', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'chinhanh_ma',
            'chinhanh_ten',
            'chinhanh_diachi',
            'chinhanh_email:email',
            'chinhanh_sodienthoai',
            // 'chinhanh_ngaythanhlap',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
