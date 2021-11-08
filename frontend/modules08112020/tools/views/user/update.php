<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\tools\models\User */

$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Update User: '.$model->username;
?>
<div class="user-update">
<div class="panel panel-info" style="margin-top: 20px;">
        <div class="panel-heading">           
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                <?= $sub_title ?>
            </h3>
        </div>
        <div class="panel-body">
    <?= $this->render('update_form', [
        'model' => $model,
    ]) ?>

</div>
    </div>
    </div>
