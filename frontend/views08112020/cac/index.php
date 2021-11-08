<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\campaign\CustomerCampaignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customer Campaigns';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-campaign-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Customer Campaign', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'campaign_id',
            'customer_id',
            'created_by',
            'created_datetime',
            //'last_updated_by',
            //'last_updated_datetime',
            //'campaign_successfull:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
