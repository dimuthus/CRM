<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\FileInput;
use kartik\widgets\DepDrop;
use frontend\models\interaction\InteractionStatus;
use frontend\modules\tools\models\user\User;
use frontend\models\cases\ChannelType;

/* @var $this yii\web\View */
/* @var $model frontend\models\interaction\Interaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inbound-interaction-create_form">
 <?= Html::activeHiddenInput($model, 'case_tbl_id') ;?>
 <input type='hidden' id='hdCaseId' value="<?= $model->case_tbl_id; ?>">

    <?php 
    
    //$model->survey_response_id=0;
    $form = ActiveForm::begin([
	'action' => ['outbound-interaction/create?case_id='.$model->case_tbl_id],'options' => ['method' => 'post'],
        //'layout' => 'horizontal',
        'id' => 'interaction-form-out',
        'validateOnSubmit' => false,
        'errorSummaryCssClass' => 'alert alert-danger',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-3 col-sm-left-align',
                'offset' => 'col-sm-offset-1',
                'wrapper' => 'col-sm-8 error-enabled-custom-field',
                'error' => '',
                'hint' => '',
            ],
        ],
	'options' => ['enctype'=>'multipart/form-data'],
    ]); ?>

    <?php

    //this is need to remove when survey is ready.
    //echo 'campaign_id='.$model->survey_response_id;
    //echo 'customer_id='.$model->customer_id;

    ?>
<!--    < ?= Html::activeHiddenInput($model, 'survey_response_id') ;?>
    < ?= Html::activeHiddenInput($model, 'customer_id') ;?>-->

 <div class="row">
	             <div class="col-sm-6">

				 <?= 
				
				 $form->field($model, 'channel_type')->dropDownList(
            ArrayHelper::map(ChannelType::find()->where('deleted != :id AND id=1', ['id'=>1])->orderBy('name')->all(), 'id', 'name')); 
         ?> </div>
        <div class="col-sm-6"><?= $form->field($model, 'interaction_status')->dropDownList(
            ArrayHelper::map(InteractionStatus::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
         ?> </div>
       
	  </div>
        <div class="row">
	        <div class="col-sm-6"> <?= $form->field($model, 'notes') ?></div>
          </div>
 <div class="row">
	        <div class="col-sm-6"> <?= Html::submitButton($model->isNewRecord ? 'Create Interaction' : 'Save Changes', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm','data-loading-text'=>'Saving...']) ?>
</div>
          </div>


    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("

    $('#interaction-form-out').submit(function (e) {
	    e.preventDefault();
        e.stopImmediatePropagation();
        //var campaign_id = 1;
       // var survey_response_id = $('#outboundinteraction-survey_response_id').val();
       // var customer_id = $('#outboundinteraction-customer_id').val();
        var case_tbl_id = $('#hdCaseId').val();


	      var reload_url = '".Url::to(['loadinteractions'])."?case_id='+case_tbl_id;
		// reload_url = reload_url.replace('customer-survey', 'outbound-interaction');
		//alert(res);
		saveInteraction($(this),reload_url,case_tbl_id);
        return false;
    });

");
?>
