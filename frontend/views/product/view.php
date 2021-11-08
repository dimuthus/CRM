<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;
/* @var $this yii\web\View */
/* @var $model frontend\models\product\Product */

$this->title = "Product: ".$model->product_name;

?>

<div class="product-view">

    <?php if(Yii::$app->user->can('Update Product')) { ?>
        <?= Html::button('<span class="glyphicon glyphicon-pencil"></span> Update', ['value'=>Url::to(['product/update','id' => $model->id]),
                                    'class' => 'btn btn-sm btn-primary console-button', 
                                    'id'=>'update-product-button', 
                                    'data-loading-text'=>'Loading...']) ?>
    <?php } ?>

    <div style="clear: both;"></div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'product_name',
	    'account_number',
            'card_number',
            'account_limit',
            'branch_name',
            'account_status',
			'cif',
			'nic',
			'relationship',
            /*[
                'attribute'=>'pop_date', 
                'format'=>['date', 'php:d M y'],
            ],*/
         
          
             [
                'attribute'=> 'createdBy.username',
                'label'=>'Created By',
            ],
            [
                'attribute'=>'created_by_datetime', 
                'format'=>['date', 'php:d M y @ h:i a']
            ],
            [
                'attribute'=>'last_upated_datetime', 
                'format'=>['date', 'php:d M y @ h:i a']
            ],
        ],
    ]) ?>

</div>

<?php
    $this->registerJs("

        $('#product-modal').find('#product-modal-title').html('".$this->title."');

        /*
            listener to handle click event of view customer details button
        */
        $('#update-product-button').click(function() {
            var btn = $(this);
            btn.button('loading');
            $('#product-modal').find('#product-modal-content')
                .load( $(this).attr('value'), function() {
                    $('#product-modal').modal('show');
                    btn.button('reset');
                });
        });
    ");
?>