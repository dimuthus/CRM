<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\Customer */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .label{
              color:black;
    }
    #user-oldpassword{
        width:100%;
    }
/*    #user-newpassword + p{
        color: yellow;
        font-family: Arial;
        font-size: 10px;
        margin-left: 25px;
        text-align: left;
        border: 1px solid transparent;
        border-radius: 4px;
        margin-bottom: 20px;
        padding: 5px;
        width:100px;
        white-space: -moz-pre-wrap !important;
         white-space: -pre-wrap;
         white-space: -o-pre-wrap;
         white-space: pre-wrap;
         word-wrap: break-word;
         word-break: break-all;
         white-space: normal;
        background-color: #dff0d8;
    border-color: #d6e9c6;
    color: #3c763d;
    }*/
    #user-newpassword + p{
        width:220px;
        white-space: -moz-pre-wrap !important;
         white-space: -pre-wrap;
         white-space: -o-pre-wrap;
         white-space: pre-wrap;
/*         word-wrap: break-word;*/
/*         word-break: break-all;*/
         white-space: normal;
    }
    #divOldPassword{
        display: inline-block;
    position: relative;
    text-align: left;
    width:75%;
    }
    </style>
	    <link rel="shortcut icon" href="<?= Url::base()?>/images/favicon.ico" type="image/x-icon" />

<div class="site-login">
    <?= Html::img('@web/images/bat.png', ['alt'=>'BAT Logo', 'id'=>'loginLogo']);?>
    <div class="loginAlert alert-success">
        Enter your old and new password.
    </div>
<div>
        <div>

    <?php $form = ActiveForm::begin([
        'fieldConfig' => ['options' => ['class' => 'label']],
    ]); ?>

<!--< ?php $form = ActiveForm::begin(['id' => 'password-form']); ?>-->
<div id="divOldPassword">
    <?= $form->field($model, 'oldpassword')->textInput(['class'=>'label', 'placeholder' => 'Current Password','maxlength' => 50])->input('password') ?>
</div>
<div id="divNewPassword">
    <?= $form->field($model, 'newpassword')->textInput(array('class'=>'label','placeholder' => 'New Password','maxlength' => 50))->input('password')  ?>
</div>
<div id="divRepeatPassword">
    <?= $form->field($model, 'repeatpassword')->textInput(array('class'=>'label','placeholder' => 'Confirm Password','maxlength' => 50))->input('password')  ?>
</div>
    <div class="form-group" style="margin-bottom:50px;">
        <label class="control-label col-sm-1 col-sm-left-align col-sm-half"></label>
        <div class="col-sm-8">
            <?= Html::submitButton('Update Password', ['class' => 'btn btn-primary btn-sm']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
    <?php
    $this->registerJs("

        $(document).ready(function($) {
        $('#divNewPassword').strength_meter();
        $('#divRepeatPassword').strength_meter();
    });
    ");
?>
