<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\modules\tools\models\user\UserRole;
use frontend\modules\tools\models\user\UserStatus;
use mdm\admin\models\searchs\AuthItem as AuthItemSearch;

use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model frontend\modules\tools\models\User */
/* @var $form yii\widgets\ActiveForm */
$searchModel = new AuthItemSearch(['rule' => 3]);
$roles = $searchModel->search(Yii::$app->request->getQueryParams());
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-1 col-sm-left-align col-sm-half',
                'offset' => '',
                'wrapper' => 'col-sm-8',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]); ?>

    Fields with <span style="color:red">*</span> are mandatory.<br/><br/>
    
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => 255, 'disabled'=>'disabled']) ?>
    <?= $form->field($model, 'role_id')->dropDownList(
            ArrayHelper::map($roles->allModels, 'name', 'name'),
            ['prompt'=>'-----']
        ); 
    ?>

    <?php if(Yii::$app->user->can('Activate/Deactivate User')) { ?>
    <?= $form->field($model, 'status_id')->widget(SwitchInput::classname(), [
        'pluginOptions'=>[
            'handleWidth'=>40,
            'size'=>'mini',
            'offColor' => 'danger',
            'onText'=>'Active',
            'offText'=>'Inactive',
         ],
    ]); ?>
    <?php } ?>


    <div class="form-group">
        <label class="control-label col-sm-1 col-sm-left-align col-sm-half"></label>
        <div class="col-sm-8">
            <?= Html::submitButton('Save Changes', ['class' => 'btn btn-primary btn-sm']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
