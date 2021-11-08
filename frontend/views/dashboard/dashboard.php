<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div id="dashboard-list">
    <?php \yii\widgets\Pjax::begin(['id' => 'dashboard-widget','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'dashboard-list-widget',
        'layout'=>"{items}\n{summary}\n{pager}",
        'showFooter'=>TRUE,
        'footerRowOptions'=>['class'=>'dashboard-footer'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'username', 
                 'label'=>'User ID', 
                'footer'=>'Total:',
            ],
            [
                'attribute'=>'total', 
                'label'=>'Total Cases Created',
                'footer'=>$summary['total'],
            ],
            [
                'attribute'=>'closed', 
                'label'=>'Total Closed Cases',
                'footer'=>$summary['closed'],
            ],
            [
                'attribute'=>'open', 
                'label'=>'Total Open Cases Created',
                'footer'=>$summary['open'],
            ],
            [
                'attribute'=>'escalated', 
                'label'=>'Total Cases Escalated',
                'footer'=>$summary['escalated'],
            ],
            [
                'attribute'=>'aging', 
                'label'=>'Max Aging'
            ],
        ]

    ]); ?>

    <?php \yii\widgets\Pjax::end(); ?>
    
</div>





