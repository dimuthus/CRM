<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurveyResponseChoice */

$this->title = 'Create Survey Question Options';
//$this->params['breadcrumbs'][] = ['label' => 'Crm Survey Response Choices', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-survey-response-choice-create">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
