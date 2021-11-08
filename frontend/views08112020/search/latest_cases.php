<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Interaction */

?>

<div id="interaction-view">
     <div class="panel panel-warning">
  <div class="panel-heading">            
  <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span>Latest Cases</h3></div>
  <div class="panel-body">

    <?php \yii\widgets\Pjax::begin(['id' => 'cases','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'dataProvider' => $cases,
        'id' => 'requests-widget',
        'layout'=>"{items}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'customer.full_name',
            'customer.preferred_name',
            [
                'attribute'=>'case_id',
                'label'=>'Case ID',
                'contentOptions'=>['style'=>'width: 100px;']
            ],
		],
        'rowOptions'   => function ($model, $key, $index, $grid) {
            return ['data-id' => $model->id];
        },
    ]); ?>

    <?php
    $this->registerJs("

        /*
        listener to handle click event of view interaction item
        */
        $('#requests-widget tbody tr').click(function (e) {
            var thisRow = $(this);
            var id = $(this).data('id');
            if(id != undefined) {
                loadCustomerCase('".Url::to(['loadcustomercase'])."/'+id);
            }
        });

    ");
    ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>
</div>
</div>
