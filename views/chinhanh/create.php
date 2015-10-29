<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Chinhanh */

$this->title = 'Create Chinhanh';
$this->params['breadcrumbs'][] = ['label' => 'Chinhanhs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chinhanh-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
