<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use frontend\models\jmccrypt\JmcCrypt;
/* @var $this yii\web\View */
/* @var $model frontend\models\Customer */


?>
<div class="customer-view">

  <div class="panel panel-warning">
  <div class="panel-heading">            
  <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span>Customer Information</h3></div>
  <div class="panel-body">

    <?php
        Modal::begin([
            'header' => '<h4>Customer Details</h4>',
            'id' => 'customer-modal',
        ]);

        echo '<div id="customer-modal-content"></div>';

        Modal::end();
    ?>

    <?php if(Yii::$app->user->can('Update Contact')) { ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Update', ['update', 'id' => $model->id,'jmc'=>"2222"],
                                ['class' => 'btn btn-xs btn-primary console-button']) ?>
    <?php } ?>

    <?= Html::button('<span class="glyphicon glyphicon-th-list"></span>  Details', [
                                    'value'=>Url::to(['customer/viewdetails','','id' => $model->id]),
                                    'class' => 'btn btn-xs btn-warning console-button',
                                    'id'=>'view-customer-details-modal-button',
                                    'data-loading-text'=>'Loading...'
    ]) ?>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'full_name',
	    'preferred_name',
            'new_nic',
            'mobile_number',
            'passport'
        ],
    ]) ?>

</div>
      </div>
    </div>
<?php
    $this->registerJs("
        /*
            listener to handle click event of view customer details button
        */
        $('#view-customer-details-modal-button').click(function() {
            var btn = $(this);
            btn.button('loading');
            var modal_content = $('#customer-modal').find('#customer-modal-content');
            modal_content.html('').load( $(this).attr('value'), function() {
                  $('#customer-modal').modal('show');
                  btn.button('reset');
            });
        });
    ");
?>
