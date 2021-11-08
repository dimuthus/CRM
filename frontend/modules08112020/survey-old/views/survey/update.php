<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurvey */

$this->title = 'Update Campaign Survey : ' . ' ' . $model->name;
//$this->params['breadcrumbs'][] = ['label' => 'Crm Surveys', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="crm-survey-update">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
