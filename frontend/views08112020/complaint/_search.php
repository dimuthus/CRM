<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\complaint\ComplaintSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="complaint-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'brand_id') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'created_by') ?>

    <?= $form->field($model, 'creation_datetime') ?>

    <?php // echo $form->field($model, 'last_modified_datetime') ?>

    <?php // echo $form->field($model, 'subbrand_id') ?>

    <?php // echo $form->field($model, 'product_id') ?>

    <?php // echo $form->field($model, 'packsize_id') ?>

    <?php // echo $form->field($model, 'color_id') ?>

    <?php // echo $form->field($model, 'batch_no') ?>

    <?php // echo $form->field($model, 'user_type_id') ?>

    <?php // echo $form->field($model, 'purchase_date') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'proof_of_purchase_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
