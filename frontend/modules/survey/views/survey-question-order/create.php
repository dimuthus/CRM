<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurveyQuestionOrder */

$this->title = 'Assign New Question';
//$this->params['breadcrumbs'][] = ['label' => 'Crm Survey Question Orders', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-survey-question-order-create">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
