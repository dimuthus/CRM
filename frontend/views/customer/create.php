<?php

use yii\helpers\Html;

   

/* @var $this yii\web\View */
/* @var $model frontend\models\Customer .*/

$this->title = Yii::$app->name . ' - Create Customer';
?>
<div class="customer-create">
<div class="panel panel-warning">
  <div class="panel-heading">            
  <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span>New Customer</h3></div>
  <div class="panel-body"><?= $this->render('_form_create', [
        'model' => $model,
    ]) ?></div>
</div>
</div>
