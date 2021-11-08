<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\campaign\Campaign */

//$this->title = 'Update Campaign: {nameAttribute}';
//$this->title = 'Update Campaign';
//$this->params['breadcrumbs'][] = ['label' => 'Campaigns', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
$this->title = "Update Campaign ";
?>
<div class="campaign-update">

      <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
