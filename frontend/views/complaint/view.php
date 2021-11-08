<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model frontend\models\complaint\Complaint */

$this->title ="Complaint". $model->id;

?>
<div class="complaint-view">

    <?php if(Yii::$app->user->can('Update Product')) { ?>
        <?= Html::button('<span class="glyphicon glyphicon-pencil"></span> Update', ['value'=>Url::to(['complaint/update','id' => $model->id]),
                                    'class' => 'btn btn-sm btn-primary console-button', 
                                    'id'=>'update-complaint-button', 
                                    'data-loading-text'=>'Loading...']) ?>
    <?php } ?>
    <div style="clear: both;"></div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
             'displaycustomerid.customer_id',
            'case_id',
            'brand.name',
            'subbrand.name',
            'product.name',
            'packsize.name',
            'color.name',
            'batch_no',
            'usertype.name',
            'purchase_date',
            'description:ntext',
            'place_of_purchase',//Added By Ahsan 15/09/2015 
            'proofofpurchase.name',          
            'creator.username',
            'creation_datetime',
            'last_modified_datetime',
           
        ],
    ]) ?>

</div>

<?php
    $this->registerJs("

        $('#complaint-modal').find('#complaint-modal-title').html('".$this->title."');

        /*
            listener to handle click event of view customer details button
        */
        $('#update-complaint-button').click(function() {
            var btn = $(this);
            btn.button('loading');
            $('#complaint-modal').find('#complaint-modal-content')
                .load( $(this).attr('value'), function() {
                    $('#complaint-modal').modal('show');
                    btn.button('reset');
                });
        });
    ");
?>