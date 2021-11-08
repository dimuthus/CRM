<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\campaign\CustomerCampaign */

$this->title = 'Update Customer Event: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Customer Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="customer-events-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
