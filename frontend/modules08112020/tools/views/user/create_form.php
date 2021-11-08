<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use mdm\admin\models\searchs\AuthItem as AuthItemSearch;
use frontend\modules\tools\models\user\UserRole;
use frontend\modules\tools\models\user\UserStatus;

/* @var $this yii\web\View */
/* @var $model frontend\modules\tools\models\User */
/* @var $form yii\widgets\ActiveForm */
$searchModel = new AuthItemSearch(['rule' => 3]);
$roles = $searchModel->search(Yii::$app->request->getQueryParams());
//var_dump($roles);
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-1 col-sm-left-align col-sm-half',
                'offset' => 'col-sm-offset-1',
                'wrapper' => 'col-sm-8 error-enabled-custom-field',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]); ?>

    Fields with <span style="color:red">*</span> are mandatory.<br/><br/>
	
	  <div class="row">
	<div class="col-sm-6"><?= $form->field($model, 'first_name')->textInput(['maxlength' => 255]) ?></div>
	<div class="col-sm-6"><?= $form->field($model, 'last_name')->textInput(['maxlength' => 255]) ?></div>
    </div>
    
    	  <div class="row">
	<div class="col-sm-6"><?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?></div>
	<div class="col-sm-6"><?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?></div>
    </div>
  
      	  <div class="row">
	<div class="col-sm-6" id="divNewPassword">    <?= $form->field($model, 'password_hash')->textInput(array('class'=>'label','placeholder' => 'New Password','maxlength' => 50))->input('password')  ?>
</div>
	<div class="col-sm-6" id="divRepeatPassword">    <?= $form->field($model, 'repeatpassword')->textInput(array('class'=>'label','placeholder' => 'Confirm Password','maxlength' => 50))->input('password')  ?>
</div>
    </div>
    <!--< ?= $form->field($model, 'password_hash')->textInput(['maxlength' => 255])->input('password')->hint('At least 6 alphanumeric characters')  ?>
    < ?= $form->field($model, 'repeatpassword')->textInput(['maxlength' => 255])->input('password')  ?> -->

		  <div class="row">
	<div class="col-sm-6"> <?= $form->field($model, 'role_id')->dropDownList(
            ArrayHelper::map($roles->allModels, 'name', 'name'),
            ['prompt'=>'-----']
        ); 
    ?></div>
	<div class="col-sm-6"></div>
    </div>
	
   

    <div class="form-group">
        <label class="control-label col-sm-1 col-sm-left-align col-sm-half"></label>
        <div class="col-sm-8">
            <?= Html::submitButton('Create User', ['class' => 'btn btn-success btn-sm']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
   <?php
    $this->registerJs("

        $(document).ready(function($) {
        $('#divNewPassword').strength_meter();
       // $('#divRepeatPassword').strength_meter();
    });
    ");
?>