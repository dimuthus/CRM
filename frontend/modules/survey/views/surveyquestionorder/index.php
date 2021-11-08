<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List of Assigned Questionnaire to Campaign-survey';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-survey-question-order-index">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
        <?= Html::a('Create new question order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
                    [
                'attribute'=>  'servey_id',
                'value'=>'survey.name',
            ],
            [
                'attribute'=>  'question_id',
                'value'=>'question.text',
            ],


            'order',
            'conditional_order_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
