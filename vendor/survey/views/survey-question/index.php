<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$sub_title = 'Campaign Survey Questionnaire';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-survey-question-index">

  <div class="panel panel-info" style="margin-top: 20px;">
        <div class="panel-heading">           
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                <?= $sub_title ?>
            </h3>
        </div>
        <div class="panel-body">
    <p>
        <?= Html::a('Create new question', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'text:ntext',
            'updated_date',
            'question_type_id',
            'is_deleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div></div></div></div>
