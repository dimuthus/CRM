<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurveyQuestion */

$this->title = 'Create Survey Question';
//$this->params['breadcrumbs'][] = ['label' => 'Crm Survey Questions', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-survey-question-create">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
