<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurveyQuestionOrder */

$this->title = $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Crm Survey Question Orders', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-survey-question-order-view">

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
		<?= Html::a('Back To List', ['index'], ['class' => 'btn btn-success']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'question_id',
            'survey.name',
            'order',
            'conditional_order_id',
        ],
    ]) ?>

</div>
