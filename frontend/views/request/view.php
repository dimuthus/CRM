<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model frontend\models\request\Request */

$this->title = "Service Request: ".$model->service_request_id;

?>
<div class="request-view">

    <?php if(Yii::$app->user->can('Update Service Request')) { ?>
        <?= Html::button('<span class="glyphicon glyphicon-pencil"></span> Update', ['value'=>Url::to(['request/update','id' => $model->id]),
                                        'class' => 'btn btn-sm btn-primary console-button', 
                                        'id'=>'update-request-button', 
                                        'data-loading-text'=>'Loading...']) ?>
    <?php } ?>

    <div style="clear: both;"></div>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'service_request_id',
            'type.name',
            'detail.name',
            'additional.name',
            'supplemental.name',
            [
                'attribute' => 'productIds',
                'value' => implode(", ", ArrayHelper::getColumn(
                        $model->getProducts()->asArray()->all(),
                        'serial_number'
                    )
                )
            ],
            'deviceObj.name',
            'enhancementObj.name',
            'softwareObj.name',
            'status.name',
            'escalated.username',
            [
                'attribute'=>'onsite_appointment_datetime', 
                'format'=>['date', 'php:d M y @ h:i a']
            ],
            'prioritytitle.name',
            'countrytitle.name',
            'center.name',
            'creator.username',
            'closer.username',
            [
                'attribute'=>'creation_datetime', 
                'format'=>['date', 'php:d M y @ h:i a']
            ],
            [
                'attribute'=>'last_modified_datetime', 
                'format'=>['date', 'php:d M y @ h:i a']
            ],
            [
                'attribute'=>'closed_datetime', 
                'format'=>['date', 'php:d M y @ h:i a']
            ],
        ],
    ]) ?>

</div>

<?php
    $this->registerJs("

        $('#request-modal').find('#request-modal-title').html('".$this->title."');

        /*
            listener to handle click event of view customer details button
        */
        $('#update-request-button').click(function() {
            var btn = $(this);
            btn.button('loading');
            $('#request-modal').find('#request-modal-content')
                .load( $(this).attr('value'), function() {
                    $('#request-modal').modal('show');
                    btn.button('reset');
                });
        });
    ");
?>