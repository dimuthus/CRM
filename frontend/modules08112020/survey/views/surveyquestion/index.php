<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Campaign Survey Questionnaire';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-survey-question-index">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
        <?= Html::a('Create new question', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'text:ntext',
            'questionType.name',
            'updated_date',
            
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
