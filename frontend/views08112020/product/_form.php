<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model frontend\models\product\Product */
/* @var $form ActiveForm */
?>
<div class="product-create_form">

     <?php $form = ActiveForm::begin([
       // 'layout' => 'horizontal',
        'id' => 'product-form',
//        'errorSummaryCssClass' => 'alert alert-danger',
//        'fieldConfig' => [
//            'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
//            'horizontalCssClasses' => [
//                'label' => 'col-sm-4 col-sm-left-align',
//                'offset' => 'col-sm-offset-1',
//                'wrapper' => 'col-sm-8 error-enabled-custom-field',
//                'error' => '',
//                'hint' => '',
//            ],
//        ],
    ]); ?>
    
     <div class="row">
	<div class="col-sm-6"><?= $form->field($model, 'product_name') ?></div>
	<div class="col-sm-6"><?= $form->field($model, 'branch_name') ?></div>
    </div>
	<!--
    <div class="row">
	<div class="col-sm-6">< ?= $form->field($model, 'account_number')->widget(MaskedInput::className(),['mask' => '9999-9999-9999-9999',])?></div>
	<div class="col-sm-6">< ?= $form->field($model, 'card_number')->widget(MaskedInput::className(),['mask' => '9999-9999-9999-9999',])?></div>
    </div> -->
	
	<div class="row">
	<div class="col-sm-6"><?= $form->field($model, 'account_number')->widget(MaskedInput::className(),[ 'name' => 'input',
            'clientOptions' => [
            'alias' =>  'numeric',
            'groupSeparator' => '',
            'autoGroup' => true
    ],])?></div>
	<div class="col-sm-6"><?= $form->field($model, 'card_number')->widget(MaskedInput::className(),[ 'name' => 'input',
            'clientOptions' => [
            'alias' =>  'numeric',
            'groupSeparator' => '',
            'autoGroup' => true,
			'rightAlign' => false
    ],])?></div>
    </div>
	
   <div class="row">
	<div class="col-sm-6"><?= $form->field($model, 'account_status') ?></div>
	<div class="col-sm-6"><?= $form->field($model, 'account_limit')->widget(MaskedInput::className(),[ 'name' => 'input-33',
            'clientOptions' => [
            'alias' =>  'decimal',
            'groupSeparator' => ',',
            'autoGroup' => true,
			'rightAlign' => false
    ],])?></div>
    </div>
	
	  <div class="row">
	<div class="col-sm-6"><?= $form->field($model, 'cif')->textInput(['id'=>'cif','readonly'=> true]) ?></div>
	<div class="col-sm-6"><?= $form->field($model, 'nic') ?></div>
    </div>
     <div class="row">
	<div class="col-sm-6"><?= $form->field($model, 'relationship') ?></div>
	<div class="col-sm-6"><?= $form->field($model, 'digital_products') ?></div>
    </div>
       <div class="row">
	<div class="col-sm-6"> <?= $form->field($model, 'product_field1') ?></div>
	<div class="col-sm-6"><?= $form->field($model, 'product_field1') ?></div>
    </div>  
	
	     <div class="row">
	<div class="col-sm-6"> <?= $form->field($model, 'currency') ?></div>
	<div class="col-sm-6"></div>
    </div> 
        
<!--        < ?= $form->field($model, 'created_by') ?>
        < ?= $form->field($model, 'last_updated_by') ?>
        < ?= $form->field($model, 'deleted') ?>
        < ?= $form->field($model, 'customer_id') ?>
        < ?= $form->field($model, 'created_by_datetime') ?>
        < ?= $form->field($model, 'last_upated_datetime') ?>-->
        
        
        
       
       
        
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- product-create_form -->

<?php
$this->registerJs("

    $('#product-form').submit(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        saveProduct($(this));
        return false;
    });

");
?>