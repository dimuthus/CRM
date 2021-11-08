<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\CasesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cases-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'case_id') ?>

    <?= $form->field($model, 'case_type') ?>

    <?= $form->field($model, 'outcome_code1') ?>

    <?php // echo $form->field($model, 'outcome_code2') ?>

    <?php // echo $form->field($model, 'outcome_code3') ?>

    <?php // echo $form->field($model, 'division_id') ?>

    <?php // echo $form->field($model, 'brand_id') ?>

    <?php // echo $form->field($model, 'subbrand_id') ?>

    <?php // echo $form->field($model, 'product_id') ?>

    <?php // echo $form->field($model, 'packsize_id') ?>

    <?php // echo $form->field($model, 'followup_datetime') ?>

    <?php // echo $form->field($model, 'case_status') ?>

    <?php // echo $form->field($model, 'escalated_to') ?>

    <?php // echo $form->field($model, 'priority_id') ?>

    <?php // echo $form->field($model, 'country_id') ?>

    <?php // echo $form->field($model, 'attachment') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'closed_by') ?>

    <?php // echo $form->field($model, 'creation_datetime') ?>

    <?php // echo $form->field($model, 'closed_datetime') ?>

    <?php // echo $form->field($model, 'last_modified_datetime') ?>

    <?php // echo $form->field($model, 'case_counter') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
