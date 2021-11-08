<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Customer */


?>
<div class="customer-update">

<div class="panel panel-warning">
  <div class="panel-heading">            
  <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span>Update Customer</h3></div>
  <div class="panel-body"><?= $this->render('_form_update', [
        'model' => $model,
    ]) ?></div>
</div>
</div>

