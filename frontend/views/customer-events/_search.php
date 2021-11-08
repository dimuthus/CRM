<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm; this doesnt work...
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\campaign\CustomerCampaignSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-events-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'event_id') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'created_by') ?>

    <?= $form->field($model, 'created_datetime') ?>

    <?php // echo $form->field($model, 'last_updated_by') ?>

    <?php // echo $form->field($model, 'last_updated_datetime') ?>

    <?php // echo $form->field($model, 'campaign_successfull')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
