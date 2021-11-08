<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Product */
$this->title = Yii::$app->name . ' - Products';
?>
<div class="product-view">


  <div class="panel panel-warning">
  <div class="panel-heading">            
  <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span>Product Information</h3></div>
  <div class="panel-body">
    <?php
        Modal::begin([
            'header' => '<h4 id="product-modal-title"></h4>',
            'id' => 'product-modal',
        ]);

        echo '<div id="product-modal-content"></div>';

        Modal::end();
    ?>
<input type='hidden' id='txt_customer_id' value="<?= $model->id?>">
    <?php if(Yii::$app->user->can('Create Product')) { ?>
        <?= Html::button('<span class="glyphicon glyphicon-plus"></span> Add Product', [
                                        'value'=>Url::to(['product/create','customer_id' => $model->id]),
                                        'class' => 'btn btn-xs btn-success console-button', 
                                        'id'=>'create-product-modal-button', 
                                        'data-loading-text'=>'Loading...'
        ]) ?>
    <?php } ?>
    
    <div style="clear: both;"></div>
    
    <?php \yii\widgets\Pjax::begin(['id' => 'products','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'dataProvider' => $products,
        'id' => 'products-widget',
        'layout'=>"{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'product_name',
                'label'=>'Product Name',
                'contentOptions'=>['style'=>'width: 100px;']

            ],
         
             [
                'attribute' =>    'account_number',
                'label'=>'Account Number',
                'contentOptions'=>['style'=>'width: 100px;']

            ],
            // [
                // 'attribute' =>    'card_number',
                // 'label'=>'Card Number',
                // 'contentOptions'=>['style'=>'width: 100px;']

            // ],
            
             // [
                // 'attribute' =>    'account_limit',
                // 'label'=>'Account Limit(LKR)',
                // 'contentOptions'=>['style'=>'width: 100px;']

            // ],
			[
                'format' => 'raw',
				'label'=>'View details',
                'value' => function ($model) {                      
                    return '<div class="edit-product-icon"><a data-id="'.$model->id.'" class="glyphicon glyphicon-menu-hamburger pukaone"></a></div>';
                },
            ],
	[
                'format' => 'raw',
				'label'=>'+ Add Case',
                'value' => function ($model) {                      
                    return '<div class="edit-product-icon"><a data-id="'.$model->id.'" class="glyphicon glyphicon-menu-hamburger pukatwo" ></a></div>';
                },
            ]
			
        ],
        'rowOptions'   => function ($model, $key, $index, $grid) {
            return ['data-id' => $model->id];
        },
		
    ]); ?>
    
    <?php
    $this->registerJs("

        /*
        listener to handle click event of view product item
        */
        $('#products-widget tbody tr').click(function (e) {
            /*
			if($(this).hasClass('active-request-row'))
                return;
            $(this).siblings().removeClass('active-request-row');
            $(this).addClass('active-request-row');
            var id = $(this).data('id');
			var customer_id = $(this).data('customer_id');
            if(id != undefined) {
                var url = '".Url::to(['cases/loadcases'])."?product_id='+ id+'&customer_id='+customer_id;
                reloadCases(url);
            }
			*/
			
			/*
			var thisRow = $(this);
            var id = $(this).data('id');
            var modal_content = $('#product-modal').find('#product-modal-content');
            if(id != undefined) {
                modal_content.html('').load( '".Url::to(['product/view'])."?id=' + id, function() {
                    $('#product-modal').modal('show');
                });

                
            }
			*/
        });

        /*
        listener to handle click event of create product button
        */
        $('#create-product-modal-button').click(function() {
            var btn = $(this);
            btn.button('loading');
            var modal_content = $('#product-modal').find('#product-modal-content');
            modal_content.html('').load( $(this).attr('value'), function() {
                $('#product-modal').modal('show');
                btn.button('reset');
            });
        });
		
		/*
        listener to handle click event of edit product icon
        */
		
		/*
        $('#products-widget tbody tr .edit-request-icon a').click(function (e) {
			
			alert('Just update');
            e.preventDefault();
            var thisRow = $(this);
            var id = $(this).data('id');
            var modal_content = $('#product-modal').find('#product-modal-content');
            if(id != undefined) {
                modal_content.html('').load( '".Url::to(['product/view'])."?id=' + id, function() {
                    $('#product-modal').modal('show');
                });

                
            }
        });
		*/
		
		  $('.pukaone').click(function (e) {
			
            e.preventDefault();
            var thisRow = $(this);
            var id = $(this).data('id');
            var modal_content = $('#product-modal').find('#product-modal-content');
            if(id != undefined) {
                modal_content.html('').load( '".Url::to(['product/view'])."?id=' + id, function() {
                    $('#product-modal').modal('show');
                });

                
            }
        });
		
		
		
		  $('.pukatwo').click(function (e) {
			
            var thisRow = $(this);
            var id = $(this).data('id');
			var customer_id = $('#txt_customer_id').val();
         
            var modal_content = $('#case-modal').find('#case-modal-content');
            if(id != undefined) {
                modal_content.html('').load( '".Url::to(['cases/create'])."?product_id='+id+'&campaign_id=0&customer_id=' + customer_id, function() {
                    $('#case-modal').modal('show');
                btn.button('reset');
                });

                
            }
        });

    ");
    ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>
</div></div>
