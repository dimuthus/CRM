<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Request */
$this->title = Yii::$app->name . ' - Service Requests';
?>
<div class="request-view">

    <h4>Service Requests</h4>

    <?php
        Modal::begin([
            'header' => '<h4 id="request-modal-title"></h4>',
            'id' => 'request-modal',
        ]);

        echo '<div id="request-modal-content"></div>';

        Modal::end();
    ?>
    
    <?php if(Yii::$app->user->can('Create Service Request')) { ?>
        <?= Html::button('<span class="glyphicon glyphicon-plus"></span> Add Service Request', [
                                        'value'=>Url::to(['request/create','customer_id' => $model->id]),
                                        'class' => 'btn btn-xs btn-success console-button', 
                                        'id'=>'create-request-modal-button', 
                                        'data-loading-text'=>'Loading...'
        ]) ?>
    <?php } ?>

    <div style="clear: both;"></div>
    
    <?= Html::hiddenInput('request_to_load', $request_to_load, ['id'=>'request_to_load']) ;?>

    <?php \yii\widgets\Pjax::begin(['id' => 'requests','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'dataProvider' => $requests,
        'id' => 'requests-widget',
        'layout'=>"{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',],
            [
                'attribute' => 'service_request_id',
                'contentOptions'=>['style'=>'width: 100px;']
            ],
            'type.name',
            'detail.name',
            // 'additional_detail',
            // 'supplemental_detail',
            // 'service_request_status',
            // 'escalated_to',
            // 'priority',
            // 'country',
            // 'service_center',
            // 'created_by',
            // 'creation_datetime',
            // 'last_modified_datetime',

            [
                'format' => 'raw',
                'value' => function ($model) {                      
                    return '<div class="edit-request-icon"><a data-id="'.$model->id.'" class="glyphicon glyphicon-menu-hamburger"></a></div>';
                },
            ]
        ],
        'rowOptions'   => function ($model, $key, $index, $grid) {
            return ['data-id' => $model->id];
        },
    ]); ?>

    <?php
    $this->registerJs("

        $(document).ready(function(){
            var request_id = $('#request_to_load').val();
            $('#requests-widget tbody tr[data-id='+request_id+']').click();
        });

        $('#requests').on('pjax:beforeSend', function(event, xhr, settings) {
            var url = '".Url::to(['interaction/'])."';
            $('#interaction-view').load(url);
        });

        $('#requests-widget tbody tr').click(function (e) {
            if($(this).hasClass('active-request-row'))
                return;
            $(this).siblings().removeClass('active-request-row');
            $(this).addClass('active-request-row');
            var id = $(this).data('id');
            if(id != undefined) {
                var url = '".Url::to(['interaction/loadinteractions'])."?request_id='+ id;
                reloadInteractions(url);
            }
        });

        /*
        listener to handle click event of edit service request item
        */
        $('#requests-widget tbody .edit-request-icon a').click(function (e) {
            e.preventDefault();
            var thisRow = $(this);
            var id = $(this).data('id');
            var modal_content = $('#request-modal').find('#request-modal-content');
            if(id != undefined) {
                modal_content.html('').load( '".Url::to(['request/view'])."?id=' + id, function() {
                    $('#request-modal').modal('show');
                });

                
            }
        });

        /*
        listener to handle click event of create request button
        */
        $('#create-request-modal-button').click(function() {
            var btn = $(this);
            btn.button('loading');
            var url = '".Url::to(['interaction/'])."';
            $('#interaction-view').load(url);
            $('#requests-widget tbody tr').removeClass('active-request-row');
            var modal_content = $('#request-modal').find('#request-modal-content');
            modal_content.html('').load( $(this).attr('value'), function() {
                $('#request-modal').modal('show');
                btn.button('reset');
            });
        });

    ");
    ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>

