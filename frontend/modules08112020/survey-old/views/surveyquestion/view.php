<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurveyQuestion */

$this->title = $model->text;
//$this->params['breadcrumbs'][] = ['label' => 'Crm Survey Questions', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-survey-question-view">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'text:ntext',
            'updated_date',
            'questionType.name',
            'is_deleted',
        ],
    ]) ?>

</div>
