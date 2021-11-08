<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->name . ' - SR Aging';
?>
<div class="requests-index">

    <h4>Pending Cases</h4>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'sr-aging-widget',
        'layout'=>"{items}",
        'columns' => [
            [
                'attribute'=>'threedays', 
                'label'=>'1-3 days',
                'contentOptions'=>['style'=>'width: 17%;']
            ],
            [
                'attribute'=>'sevendays', 
                'label'=>'>4-7 days',
                'contentOptions'=>['style'=>'width: 17%;']
            ],
            [
                'attribute'=>'fifteendays', 
                'label'=>'>7-15 days',
                'contentOptions'=>['style'=>'width: 17%;']
            ],
            [
                'attribute'=>'thirthydays', 
                'label'=>'>15-30 days',
                'contentOptions'=>['style'=>'width: 17%;']
            ],
            [
                'attribute'=>'moredays', 
                'label'=>'>30 days',
                'contentOptions'=>['style'=>'width: 17%;']
            ],
            [
                'attribute'=>'total', 
                'contentOptions'=>['style'=>'width: 17%;']
            ],
        ]

    ]); ?>

</div>
