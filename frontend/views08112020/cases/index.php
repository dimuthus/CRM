<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CasesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model frontend\models\Product */

$this->title = Yii::$app->name . ' - Cases';
?>
<div class="cases-index">

     <div class="panel panel-warning">
  <div class="panel-heading">            
  <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span>Cases</h3></div>
  <div class="panel-body">
  <?php
        Modal::begin([
            'header' => '<h4 id="case-modal-title"></h4>',
            'id' => 'case-modal',
        ]);

        echo '<div id="case-modal-content"></div>';

        Modal::end();
    ?>
    
    <?php if(Yii::$app->user->can('Create Service Request')) { ?>
        <?= Html::button('<span class="glyphicon glyphicon-plus"></span> Add Case', [
                                        'value'=>Url::to(['cases/create','customer_id' => $model->id,'campaign_id'=>$campId,'product_id'=>0]),
                                        'class' => 'btn btn-xs btn-success console-button', 
                                        'id'=>'create-case-modal-button', 
                                        'data-loading-text'=>'Loading...'
        ]) ?>
        <?= Html::button('<span class="glyphicon glyphicon-plus"></span> Add For a Campaign', [
                                        'value'=>Url::to(['cac/create','customer_id' => $model->id]),
                                        'class' => 'btn btn-xs btn-primary console-button', 
                                        'id'=>'create-campaign-modal-button', 
                                        'data-loading-text'=>'Loading...'
        ]) ?>
    <?php } ?>

    <div style="clear: both;"></div>
    
  <?= Html::hiddenInput('case_to_load', $case_to_load, ['id'=>'case_to_load']) ;?>

    <?php \yii\widgets\Pjax::begin(['id' => 'cases','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
    <?= GridView::widget([
        'dataProvider' => $cases,
        'id' => 'cases-widget',
        'layout'=>"{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'case_id',
            [
              'attribute'=>'createdBy.username',
                'label'=>'Created By'
           ],
            'created_datetime',
            [
                'format' => 'raw',
                'label' => 'View Details',
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
            var case_id = $('#case_to_load').val();
            $('#cases-widget tbody tr[data-id='+case_id+']').click();
        });

        $('#cases').on('pjax:beforeSend', function(event, xhr, settings) {
			
            var url = '".Url::to(['inbound-interaction/'])."';
            $('#interaction-view').load(url);
        });

        $('#cases-widget tbody tr').click(function (e) {
            if($(this).hasClass('active-request-row'))
                return;
            $(this).siblings().removeClass('active-request-row');
            $(this).addClass('active-request-row');
            var id = $(this).data('id');
            if(id != undefined) {
                var url = '".Url::to(['inbound-interaction/loadinteractions'])."?case_id='+ id;
                reloadInteractions(url);
            }
        });

        /*
        listener to handle click event of edit service request item
        */
        $('#cases-widget tbody .edit-request-icon a').click(function (e) {
            e.preventDefault();
            var thisRow = $(this);
            var id = $(this).data('id');
            var modal_content = $('#case-modal').find('#case-modal-content');
            if(id != undefined) {
                modal_content.html('').load( '".Url::to(['cases/view'])."?id=' + id+'&ob=N', function() {
                    $('#case-modal').modal('show');
                });

                
            }
        });

        /*
        listener to handle click event of create request button
        */
        $('#create-case-modal-button').click(function() {
            var btn = $(this);
            btn.button('loading');
            var url = '".Url::to(['inbound-interaction/'])."';
            $('#interaction-view').load(url);
            $('#cases-widget tbody tr').removeClass('active-case-row');
            var modal_content = $('#case-modal').find('#case-modal-content');
            modal_content.html('').load( $(this).attr('value'), function() {
                $('#case-modal').modal('show');
                btn.button('reset');
            });
        });
        

        /*
        listener to handle click event of assign campaign button
        */
        $('#create-campaign-modal-button').click(function(e) {
            var btn = $(this);
            btn.button('loading');
             e.preventDefault();
        e.stopImmediatePropagation();
            var url = '".Url::to(['inbound-interaction/'])."';
            $('#interaction-view').load(url);
            $('#cases-widget tbody tr').removeClass('active-case-row');
            var modal_content = $('#case-modal').find('#case-modal-content');
            modal_content.html('').load( $(this).attr('value'), function() {
                $('#case-modal').modal('show');
                btn.button('reset');
            });
        });
        

    ");
    ?>
    <?php \yii\widgets\Pjax::end(); ?>

  </div></div></div>
