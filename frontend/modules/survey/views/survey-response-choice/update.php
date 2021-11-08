<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurveyResponseChoice */

$this->title = 'Update Survey Question Options : ' . ' ' . $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Crm Survey Response Choices', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="crm-survey-response-choice-update">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
