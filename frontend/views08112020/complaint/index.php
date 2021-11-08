<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Product */
$this->title = Yii::$app->name . ' - Complaints';
?>
<div class="complaint-view">

    <h4>Complaint Detail</h4>

    <?php
        Modal::begin([
            'header' => '<h4 id="complaint-modal-title"></h4>',
            'id' => 'complaint-modal',
        ]);

        echo '<div id="complaint-modal-content"></div>';

        Modal::end();
    ?>

    <?php if(Yii::$app->user->can('Create Product')) { ?>
        <?= Html::button('<span class="glyphicon glyphicon-plus"></span> Add Complaint', [
                                        'value'=>Url::to(['complaint/create','customer_id' => $model->id]),
                                        'class' => 'btn btn-xs btn-success console-button', 
                                        'id'=>'create-complaint-modal-button', 
                                        'data-loading-text'=>'Loading...'
        ]) ?>
    <?php } ?>
    
    <div style="clear: both;"></div>
    
    <?php \yii\widgets\Pjax::begin(['id' => 'complaint','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'dataProvider' => $complaints,
        'id' => 'complaints-widget',
        'layout'=>"{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
                   
             [
                'attribute' =>    'brand.name',
                'label'=>'Brand',
                'contentOptions'=>['style'=>'width: 100px;']

            ],
          [
                'attribute' =>    'product.name',
                'label'=>'Product',
                'contentOptions'=>['style'=>'width: 100px;']

            ],
            [
                'attribute' =>    'description',
                'label'=>'Product Description',
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
        listener to handle click event of view complaint item
        */
        $('#complaints-widget tbody tr').click(function (e) {
            var thisRow = $(this);
            var id = $(this).data('id');
           
            var modal_content = $('#complaint-modal').find('#complaint-modal-content');
            if(id != undefined) {
                modal_content.html('').load( '".Url::to(['complaint/view'])."?id=' + id, function() {
                    $('#complaint-modal').modal('show');
                });

                
            }
        });

        /*
        listener to handle click event of create complaint button
        */
        $('#create-complaint-modal-button').click(function() {
            var btn = $(this);
            btn.button('loading');
            var modal_content = $('#complaint-modal').find('#complaint-modal-content');
            modal_content.html('').load( $(this).attr('value'), function() {
                $('#complaint-modal').modal('show');
                btn.button('reset');
            });
        });

    ");
    ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>

