<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use frontend\modules\survey\models\CrmSurvey;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List of Assigned Questionnaire to Campaign-survey';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-survey-question-order-index">

    <h4><?= Html::encode($this->title) ;
         $surveys=[];
        $surveys=ArrayHelper::map(CrmSurvey::find()->where('is_deleted != :id', ['id'=>1])->orderBy('id')->all(), 'id', 'name');
        
        ?></h4>

    <p>
        <?= Html::a('Assign question to campaign-survey', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

                    [
                'attribute'=>  'surveyname',
                'value'=>'survey.name',
                'filter'=>[$surveys ],
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
