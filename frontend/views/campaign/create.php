<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\campaign\Campaign */

$sub_title = 'Create Campaign';
?>
<div class="campaign-create">

  <div class="panel panel-info" style="margin-top: 20px;">
        <div class="panel-heading">           
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                <?= $sub_title ?>
            </h3>
        </div>
        <div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div></div></div>