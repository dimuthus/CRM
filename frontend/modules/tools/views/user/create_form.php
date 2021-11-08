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
				 // 'enableAjaxValidation' => true,
            ],
        ],
    ]); ?>

    Fields with <span style="color:red">*</span> are mandatory.<br/><br/>
    
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'password_hash')->hiddenInput(['value'=>'P@55w0rd'])->label(false); ?>
    <?= $form->field($model, 'repeatpassword')->hiddenInput(['value'=>'P@55w0rd'])->label(false); ?>
    <?= $form->field($model, 'role_id')->dropDownList(
            ArrayHelper::map($roles->allModels, 'name', 'name'),
            ['prompt'=>'-----']
        ); 
    ?>

    <div class="form-group">
        <label class="control-label col-sm-1 col-sm-left-align col-sm-half"></label>
        <div class="col-sm-8">
            <?= Html::submitButton('Create User', ['class' => 'btn btn-success btn-sm']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
