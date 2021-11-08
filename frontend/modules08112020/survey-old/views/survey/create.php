<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurvey */

$this->title = 'Create Campaign Survey';
//$this->params['breadcrumbs'][] = ['label' => 'Crm Surveys', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-survey-create">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
