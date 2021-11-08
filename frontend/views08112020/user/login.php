<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <?= Html::img('@web/images/bat.png', ['alt'=>'BAT Logo', 'id'=>'loginLogo']);?>
    <div class="loginAlert alert-success">
        Enter your username and password.
    </div>

    <div>
        <div>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username')->textInput(array('placeholder' => 'Username'))->label(false);  ?>
                <?= $form->field($model, 'password')->passwordInput(array('placeholder' => 'Password'))->label(false); ?>
                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'id' => 'loginButton', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
