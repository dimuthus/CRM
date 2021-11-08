<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\survey\models\CrmSurveySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Campaign Survey List';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-survey-index">

    <h4><?= Html::encode($this->title) ?></h4>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create new survey', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description:ntext',
           // 'updated',
            'opening_date',
             'closing_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
