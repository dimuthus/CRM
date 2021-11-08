<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model frontend\models\cases\Cases */

$this->title = "Case: ".$model->case_id;
?>
<div class="cases-view">

   <?php if(Yii::$app->user->can('Update Service Request')) { ?>
        <?= Html::button('<span class="glyphicon glyphicon-pencil"></span> Update', ['value'=>Url::to(['cases/update','id' => $model->id]),
                                        'class' => 'btn btn-sm btn-primary console-button', 
                                        'id'=>'update-case-button', 
                                        'data-loading-text'=>'Loading...']) ?>
    <?php } ?>

    <div style="clear: both;"></div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'case_id',
            ['attribute'=>'campaign0.name','label'=>'Campain'],
            ['attribute'=>'channelType.name', 'label'=>'Channel Type'],
            ['attribute'=>'caseStatus.name','label'=>'Case Status'],
            ['attribute'=>'escalatedTo.username','label'=>'Escalated To'],
            ['attribute'=>'attachment_name', 'format'=>'raw','value'=>$model->getDocument($model)],
            ['attribute'=>'caseCategory1.name','label'=>'Case Category1'],
            ['attribute'=>'caseCategory2.name','label'=>'Case Category2'],
            ['attribute'=>'caseCategory3.name','label'=>'Case Category3'],
            'case_note',
			'location_of_atm',
			'debit_card_number',
			'branch_department',
			'credit_card_number',
			'charge_disputed__note',
			'reference_number_of_application',
			'application_date',
			'new_credit_limit_requested',
			'current_credit_limit',
			'reason_for_change_in_credit_limit',
			'mobile_number_requested_on',
			'current_debit_limit',
			'new_debit_limit_requested',
			'reason_for_change_in_debit_limit',
			'charge_disputed',
			'product_interest_in',
			'responsible_officer',
			'TAT',
			'merchant_number',
			'amount',
			'mobile_device',
			'app_version',
            'case_field1',
            'case_field2',
            'case_field3',
            'case_field4',
            'case_field5',
            ['attribute'=>  'createdBy.username','label'=>'Created By'],

            
//            'transactionType0.name',
//            'hotline',
//            'awb',
//            'total_box',
//            'product0.name',
//            'purchased_at',
//            'calledFrom.name',
           
            'created_datetime',
            ['attribute'=>  'lastUpdatedBy.username','label'=>'Updated By'],
          
            'last_updated_datetime',
//            'replacementDeliveryMethod.name',
//            'firstCallResolution.name',
//         
//            'complain:boolean',
//            'callback',
            'customer_id',
            
   
        ],
    ]) ?>

</div>
<?php
    $this->registerJs("

        $('#case-modal').find('#case-modal-title').html('".$this->title."');

        /*
            listener to handle click event of view customer details button
        */
        $('#update-case-button').click(function() {
            var btn = $(this);
            btn.button('loading');
            $('#case-modal').find('#case-modal-content')
                .load( $(this).attr('value'), function() {
                    $('#case-modal').modal('show');
                    btn.button('reset');
                });
        });
    ");
?>